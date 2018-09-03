# 宅音乐播放器
--------
宅音乐播放器，HTML5网页播放器，集成后台管理及API调用，目前正在开发中，敬请关注
## 技术栈
- 后端：thinkphp 5.1
- 前端：layui
- 数据库：mysql

## 安装
### 依赖
- composer
- php 5.6+
- mysql 5.5+
### 步骤
安装php依赖包
```
composer install
```
配置数据库，配置链接数据库名以及用户名密码
````
/config/database.php
````
创建数据库
```
字符编码：utf8 -- UTF-8 Unicode
导入数据库脚本，脚本位置extend/database
```
### 伪静态配置
#### nginx
```
  location / {
      index  index.htm index.html index.php;
      #访问路径的文件不存在则重写URL转交给ThinkPHP处理
      if (!-e $request_filename) {
         rewrite  ^/(.*)$  /index.php?s=$1  last;
         break;
      }
  }
```
#### apache
项目自带apache静态化无需配置
### 启动项目
- 添加public为web根目录
- 若为apache服务器则默认伪静态，nginx可自行配置伪静态
## 预览
### 首页
![webconfig](preview/index.png)
### 登陆页面
![webconfig](preview/login.png)
### 后台首页
![webconfig](preview/admin_index.png)
### 后台播放器管理页面
![webconfig](preview/admin_player.png)
### 后台歌单管理页面
![webconfig](preview/admin_song_sheet.png)
