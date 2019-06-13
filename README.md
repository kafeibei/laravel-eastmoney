# 股票分析功能

> 个人兴趣，接口为抓取所得，仅供学习交流，如有侵权，请联系


## 1. Introduction - 简介
基于某某财经网站的股票分析工具；内容包括：获取股票信息，本地存储，分析统计，图标展示；技术栈包括：

* Lavarel (sqlite + redis)
* Vue2 全家桶(vue2 + axios + vue-router + vuex)
* Sass + echarts


## 2. Function - 功能说明
### 2.1 数据生成

#### 2.1.1 数据库生成

#### 2.1.2 数据库更新

### 2.2 数据分析

#### 2.2.1 数据计算

#### 2.2.2 数据过滤

### 2.3 数据展示

#### 2.3.1 图标展示

#### 2.3.2 表格展示


## 3. Installation - 安装

### 2.1 环境配置
#### 2.1.1 安装php

#### 2.1.2 安装composer

#### 2.1.4 安装node

#### 2.1.3 安装redis


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
```
启动完成后，打开浏览器访问 http://127.0.0.1:8002，就可以看到项目页面了