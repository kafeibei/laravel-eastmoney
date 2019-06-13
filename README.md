# 股票分析功能

> 个人兴趣，接口为抓取所得，仅供学习交流，如有侵权，请联系


## 1. Introduction - 简介
基于某某财经网站的股票分析工具；内容包括：获取股票信息，本地存储，分析统计，图标展示；技术栈包括：

* Lavarel (sqlite + redis)
* Vue2 全家桶(vue2 + axios + vue-router + vuex)
* elementUI + Sass + echarts


## 2. Function - 功能说明
### 2.1 数据生成
<img src="https://raw.githubusercontent.com/kafeibei/laravel-eastmoney/master/resources/images/readme/form.png" />

#### 2.1.1 数据库生成
* 获取输入ID范围取得的数据，根据时间过滤特定时间范围里面的值
* 一个总表记录查询的ID值，每个ID查询的数据单独建stockId表
* 每次生成新的数据直接替换原有表里的数据

#### 2.1.2 数据库更新
* 查询stockId表的最后一条数据的date字段，把date之后的数据插入表中，更新数据

### 2.2 数据分析
<img src="https://raw.githubusercontent.com/kafeibei/laravel-eastmoney/master/resources/images/readme/east.png" />

#### 2.2.1 数据计算

#### 2.2.2 数据过滤

### 2.3 数据展示

#### 2.3.1 图标展示
<img src="https://raw.githubusercontent.com/kafeibei/laravel-eastmoney/master/resources/images/readme/search_echart.png" />

* 从数据库中取特定stockid表里的所有数据进行图标展示

#### 2.3.2 表格展示
<img src="https://raw.githubusercontent.com/kafeibei/laravel-eastmoney/master/resources/images/readme/search_table.png" />

* 从数据库中取特定stockid表里的数据进行分页展示
* 可分别根据date、tv_radio、to_radio、tt_radio进行升序或者降序展示

## 3. Installation - 安装

### 2.1 环境配置
#### 2.1.1 安装php
```
brew install php -g
```

#### 2.1.2 安装composer
```
brew install composer -g
```

#### 2.1.4 安装node
```
# 安装n模块
npm install -g n
# 升级到最新稳定版
n stable
# 安装指定版本
n v6.11.5
```

#### 2.1.3 安装redis
```
brew install redis -g
```


### 2.2 安装步骤
```
# 下载仓库
git clone https://github.com/kafeibei/laravel-eastmoney.git
# 进入项目根目录，安装php依赖
composer install
# 安装vue依赖 
npm install/ cnpm install
# 启动服务
php artisan serve
# 生成key
php artisan key:generate
# bootstrap/cache目录给权限
# storage目录给权限
```
启动完成后，打开浏览器访问 http://127.0.0.1:8002 ，就可以看到项目的数据生成页了