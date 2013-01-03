
目录结构说明
--------
|- bin          命令行工具目录
|  |- daemon     守护进程目录
|  |- update     版本升级脚本目录
|- config       配制文件目录
|- src          代码目录
|- tests        单元测试目录
|- var          数据目录 (只有这个目需要写权限)
|  |- cache      文件缓存
|  |- locks      文件锁
|  |- logs       日志文件目录
|  |- tmp        临时文件目录 (外部不可以访问)
|  |- upload     文件上传目录 (web服务器需要配制指向这个目录)
|  |  |- tmp      临时文件目录 (外部可访问)
|- vendors      第3方代码库目录
|- views        模板文件目录
|- webroot      web入口目录
|  |- www        前台入口
|  |- admin      后台入口
