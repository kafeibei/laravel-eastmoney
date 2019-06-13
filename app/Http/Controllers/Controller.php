<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

set_time_limit(0);

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function output ($data = [],$status = 200,$header = []) {
      return response()->json($data, $status, $header);
    }

    protected function listToPage (LengthAwarePaginator $page) {
      $items = $page->items();
      return [
        'page'  => [
          'total' => $page->total(),
          'current_page'  => $page->currentPage(),
          'last_page' => $page->lastPage()
        ],
        'data'  => $items
      ];
    }

    protected function supplyNumber ($str, $num = 6)
    {
      if (strlen($str) === $num ) {
        return $str;
      }
      while (1) {
        $str = '0'.$str;
        if (strlen($str) === $num) {
          break;
        }
      }
      return $str;
    }

    protected function curl ($id, $url = '')
    {
      $response = array();
      $curlPing = curl_init($url);
      curl_setopt($curlPing, CURLOPT_TIMEOUT, 5);
      curl_setopt($curlPing, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($curlPing, CURLOPT_RETURNTRANSFER, true);
      $data = curl_exec($curlPing);
      $result = curl_multi_getcontent($curlPing);
      if ($result) {
        $substr = json_decode(substr($result, 1, -1));
        if (gettype($substr) == 'object') {
          $output = (object)[
            'result' => $substr,
            'id' => $id
          ];
          $response[$id] = $output;
          curl_close($curlPing);
          return $response;
        }
      }
      curl_close($curlPing);
      return '没有加载到数据';
    }

    public function curls ($urls)
    {
      $response = array();
      if (empty($urls)) {
        return $response;
      }
      $chs = curl_multi_init();
      $map = array();
      foreach($urls as $id => $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_multi_add_handle($chs, $ch);
        $map[strval($ch)] = $id;
      }
      do {
        if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
          if ($status != CURLM_OK) { break; } //如果没有准备就绪，就再次调用curl_multi_exec
          while ($done = curl_multi_info_read($chs)) {
            $result = curl_multi_getcontent($done["handle"]);
            $id = $map[strval($done["handle"])];
            if ($result) {
              $substr = json_decode(substr($result, 1, -1));
              if (gettype($substr) == 'object') {
                $output = (object)[
                  'result' => $substr,
                  'id' => $id
                ];
                $response[$id] = $output;
              }
            }
            curl_multi_remove_handle($chs, $done['handle']);
            curl_close($done['handle']);
            //如果仍然有未处理完毕的句柄，那么就select
            if ($active > 0) {
              curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
            }
          }
        }
      }
      while($active > 0); //还有句柄处理还在进行中
      curl_multi_close($chs);
      return $response;
    }
}
