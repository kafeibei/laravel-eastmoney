<?php
namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Events\ErrorHandle;


class pdfmController extends Controller
{

  public function createDB () {
    $res = $this->byValidate();
    if ($res) {
      return $this->output((object)[
        'code'  => 500,
        'message' => $res
      ]);
    }
    $splitIds = $this->splitIds();
    $len = count($splitIds);
    for ($i=0; $i<$len; $i++) {
      $pdfm = $this->curlEastpdfm($splitIds[$i]);
      $list = $this->handleByDate($pdfm, 'create');
      foreach($list as $res) {
        $stock = Stock::where(['stock_id'=>$res->stock_id])->first();
        if (!$stock) {
          Stock::insert([
            'stock_id' => $res->stock_id,
            'company' =>$res->name
          ]);
        }
        if ($res->data && $res->data[0]) {
          $eastTable = 'east_'.$res->stock_id;
          if(!Schema::hasTable($eastTable)){
              Schema::create($eastTable, function ($table) {
                $table->increments('id');
                $table->string('date');
                $table->string('sprice')->default('0.00')->comment('开盘价');
                $table->string('eprice')->default('0.00')->comment('收盘价');
                $table->string('trade_vol')->comment('成交量');
                $table->string('turnover')->comment('成交额');
                $table->string('tv_ratio')->default('0.00');
                $table->string('to_ratio')->default('0.00');
                $table->string('tt_ratio')->default('0.00');
                $table->string('avg5')->default('0.00');
                $table->string('avg10')->default('0.00');
                $table->string('avg20')->default('0.00');
                $table->string('avg30')->default('0.00');
              });
          }
          $rows = array();
          foreach ($res->data as $value) {
            $east = DB::table($eastTable)->where(['date'=>$value->date])->first();
            if (!$east) {
              $rows[] = (object)[
                'stock_id'  => $res->stock_id,
                'date' => $value->date,
                'sprice' => $value->sprice,
                'eprice' => $value->eprice,
                'trade_vol' => $value->trade_vol,
                'turnover' => $value->turnover
              ];
            }
          }
          $this->downFile($rows, [$res->stock_id, 'create']);
        }
      }
    }
    return $this->output((object)[
      'code'  => 200,
      'data'  => $list
    ]);
  }

  public function updateDB () {
    $res = $this->byValidate();
    if ($res) {
      return $this->output((object)[
        'code'  => 500,
        'message' => $res
      ]);
    }
    $splitIds = $this->splitIds();
    $len = count($splitIds);
    for ($i=0; $i<$len; $i++) {
      $pdfm = $this->curlEastpdfm($splitIds[$i]);
      $list = $this->handleByDate($pdfm, 'update');
      foreach($list as $res) {
        $eastTable = 'east_'.$res->stock_id;
        if(!Schema::hasTable($eastTable)){
            Schema::create($eastTable, function ($table) {
              $table->increments('id');
              $table->string('date');
              $table->string('sprice')->default('0.00')->comment('开盘价');
              $table->string('eprice')->default('0.00')->comment('收盘价');
              $table->string('trade_vol')->comment('成交量');
              $table->string('turnover')->comment('成交额');
              $table->string('tv_ratio')->default('0.00');
              $table->string('to_ratio')->default('0.00');
              $table->string('tt_ratio')->default('0.00');
            });
        }
        if ($res->data && $res->data[0]) {
          $east = DB::table($eastTable)->get()->all();
          if ($east) {
            $query = array_pop($east);
          } else {
            $query = (object)[
              'date'  => ''
            ];
          }
          $rows = array();
          foreach ($res->data as $value) {
            if ($query->date && $value->date > $query->date) {
              $rows[] = (object)[
                'date' => $value->date,
                'sprice' => $value->sprice,
                'eprice' => $value->eprice,
                'trade_vol' => $value->trade_vol,
                'turnover' => $value->turnover
              ];
            } else if (!$query->date) {
              $rows[] = (object)[
                'date' => $value->date,
                'sprice' => $value->sprice,
                'eprice' => $value->eprice,
                'trade_vol' => $value->trade_vol,
                'turnover' => $value->turnover
              ];
            }
          }
          $this->downFile($rows, [$res->stock_id, 'update']);
          // DB::table($eastTable)->insert($rows);
        }
      }
    }
    return $this->output((object)[
      'code'  => 200,
      'data'  => $list
    ]);
  }

  private function splitIds () {
    $ids[] = request('start_id');
    $ids[] = request('end_id');
    $output = array();
    if (!$ids[1]) {
      $output[] = [$ids[0]];
    } else {
      $len = $ids[1] - $ids[0];
      if ($len > 0) {
        $count = 20;
        $num = ceil($len/$count);
        for ($i = 0; $i < $num; $i++) {
          $idarr = array();
          $end = ($i+1)*$count > $len ? $len : ($i+1)*$count;
          for ($j = $i*$count; $j < $end; $j++) {
            $idarr[] = $this->supplyNumber($j + $ids[0]);
          }
          $output[] = $idarr;
        }
      }
    }
    return $output;
  }

  public function downFile ($data, $params)
  {
    $template = "
        CREATE TABLE east_{liv_stock_id} (
         id INTEGER PRIMARY KEY AUTOINCREMENT,
         date VARCHAR(12),    -- 日期
         sprice VARCHAR(3),   -- 开盘价
         eprice VARCHAR(3),   -- 收盘价
         trade_vol VARCHAR(24), -- 成交量
         turnover  VARCHAR(24),  -- 成交额
         tv_ratio VARCHAR(3) DEFAULT '0.00',
         to_ratio VARCHAR(3) DEFAULT '0,00',
         tt_ratio VARCHAR(3) DEFAULT '0.00'
    );";
    // $html = preg_replace("{{liv_stock_id}}", $params[0], $template);
    $html = "BEGIN;";
    foreach($data as $item) {
      $html .= "
      INSERT INTO east_".$params[0]." (date, sprice, eprice, trade_vol, turnover) VALUES ('$item->date', '$item->sprice', '$item->eprice', '$item->trade_vol', '$item->turnover');";
    }
    $html.= "
    COMMIT;";
    if ($params[1] === 'create') {
      file_put_contents('/Users/hujinxia/Documents/creek/haibo.Code/stock/hdata/east_'.$params[0], $html);
      exec("/usr/bin/sqlite3 /Users/hujinxia/Documents/creek/haibo.Code/stock/stock.db < /Users/hujinxia/Documents/creek/haibo.Code/stock/hdata/east_".$params[0]);
    } else {
      file_put_contents('/Users/hujinxia/Documents/creek/haibo.Code/stock/hdata/east_'.$params[0].'_update', $html);
      exec("/usr/bin/sqlite3 /Users/hujinxia/Documents/creek/haibo.Code/stock/stock.db < /Users/hujinxia/Documents/creek/haibo.Code/stock/hdata/east_".$params[0].'_update');
    }
  }

  public function calcDB () {
    $command = "/Users/hujinxia/Documents/creek/haibo.Code/stock/capp/stockal -c";
    $retal = array();
    try {
      exec($command, $retal);
      var_dump($retal);
    } catch (\Exception $exception){
      event(new ErrorHandle($exception,'ffmpeg'));
    }
  }

  public function filterDB () {
    $thresh = request('thresh');
    $command = "/Users/hujinxia/Documents/creek/haibo.Code/stock/capp/stockal -f ".$thresh;
    $retal = array();
    try {
      exec($command, $retal);
      var_dump($retal);
    } catch (\Exception $exception){
      event(new ErrorHandle($exception,'ffmpeg'));
    }
  }

  public function searchDB () {
    $stock_id = request('stock_id');
    $eastTable = 'east_'.$stock_id;
    if(!Schema::hasTable($eastTable)){
      return $this->output((object)[
        'code'  => 500,
        'message' => '数据不存在'
      ]);
    }
    $east = DB::table($eastTable)->orderBy('date', 'asc')->get()->all();
    return $this->output((object)[
      'code'  => 200,
      'data' => $east
    ]);
  }

  private function curlPdfm () {
    $ids[] = request('start_id');
    $ids[] = request('end_id');
    $dates[] = request('start_date');
    $dates[] = request('end_date');
    $pdfm = array();
    if ($ids && $ids[0]) {
      $res = $this->byValidate($ids, $dates);
      if ($res) {
        return $this->output((object)[
          'code'  => 500,
          'message' => $res
        ]);
      }
      $pdfm = $this->curlEastpdfm($ids);
      if (gettype($pdfm) == 'string') {
        return $this->output((object)[
          'code'  => 500,
          'message' => $pdfm
        ]);
      }
    }
    return $this->output((object)[
      'code'  => 200,
      'data'  => $pdfm
    ]);
  }

  // 从第三方获取数据
  public function curlEastpdfm ($ids) {
    $url = "http://pdfm.eastmoney.com/EM_UBG_PDTI_Fast/api/js?rtntype=6&type=k&authorityType=fa";
    $output = array();
    $len = count($ids);
    $urls = array();
    for ($i = 0; $i < $len; $i++) {
      $result = $this->storageCurls($ids[$i]);
      if ($result) {
        array_push($output, $result);
      } else {
        $urls[$ids[$i]] = $url."&id=".$ids[$i].'1';
      }
    }
    // curl_mutli同时并发太多会导致CPU占用过高，反而
    // 导致请求失败，这里每次最多只生成20个并发
    $response = $this->curls($urls);
    $date = date("Y-m-d");
    foreach ($response as $item) {
      $tag = 'east:'.$item->id;
      Cache::put($tag.':'.$date, json_encode($item), now()->addHours(5));
    }
    return array_merge($response, $output);
  }

  private function storageCurls ($id) {
    $date = date("Y-m-d");
    $tag = 'east:'.$id;
    $result = Cache::get($tag.':'.$date);
    if ($result) {
      return json_decode($result);
    }
    return '';
  }

  // 验证数据有效性
  public function byValidate () {
    $ids[] = request('start_id');
    $ids[] = request('end_id');
    $dates[] = request('start_date');
    $dates[] = request('end_date');
    $message = '';
    if (strtotime($dates[0]) && !strtotime($dates[0]) || $dates[1] && !strtotime($dates[1])) {
      $message = '时间格式错误';
    }
    if (!$message) {
      if (!$ids[0]) {
        $message = '请输入有效的id';
      }
      if (!$message && $ids[1]) {
        if ($ids[0] >= $ids[1]) {
          $message = '参数值第一个不能大于第二项';
        } else if ($ids[1] - $ids[0] > 10) {
          // $message = '加载的数据太多';
        }
      } else if (strlen($ids[0]) != 6 || $ids[1] && strlen($ids[1]) != 6) {
        $message = 'id格式错误';
      }
    }
    return $message;
  }

  // 根据日期刷选数据
  private function handleByDate ($data, $type) {
    $dates[] = request('start_date');
    $dates[] = request('end_date');
    $middle = array();
    $output = array();
    foreach($data as $res) {
      if ($res->result) {
        $id = $res->result->code;
        $middle[$id] = array();
        if ($res->result->data) {
          $result = $res->result->data;
          $len = count($result);
          $name = $res->result->name;
          for ($i = 0; $i < $len; $i++) {
            $arr = explode(',', $result[$i]);
            if ($dates[0] && $type === 'create') {
              if ($dates[1]) {
                if ($arr[0] >= $dates[0] && $arr[0] <= $dates[1]) {
                  $middle[$id][] = $this->assignData($arr);
                }
              } else {
                if ($dates[0] == $arr[0]) {
                  $middle[$id][] = $this->assignData($arr);
                  break;
                }
              }
            } else {
              $middle[$id][] = $this->assignData($arr);
            }
          }
          $output[] = (object)[
            'stock_id' => $id,
            'name' => $name,
            'data' => $middle[$id]
          ];
        }
      }
    }
    return $output;
  }

  public function assignData ($arr) {
    $each = (object)[
      'date' => $arr[0],
      'sprice' => $arr[1],
      'eprice' => $arr[2],
      'trade_vol' => $arr[5],
      'turnover' => $arr[6]
    ];
    return $each;
  }
}
