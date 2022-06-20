xdebugの有効化

[Xdebug: Documentation » Xdebug 2 から 3 へのアップグレード](https://xdebug.org/docs/upgrade_guide/ja)
[Xdebug: ドキュメント » すべての設定](https://xdebug.org/docs/all_settings)
[Xdebug3\.0\.0がリリースされたので、ver2からの雑な設定コンバート \- Qiita](https://qiita.com/naoyukik/items/ef83fd9f59228694556d)

/etc/php.d/20-xdebug.ini
に設定する場合

xdebug.client_host=host.docker.internal 
xdebug.mode=debug
(無効の場合 xdebug.mode=)

xdebugのデバッグポートは xdebug3 で 9003

環境変数を使う
docker-compose で environment に設定

XDEBUG_MODE: debug
XDEBUG_CONFIG: client_host=host.docker.internal

XDEBUG_MODE: debug,develop
でもいいかも

## 設定を見る
コンソール
bash-4.2# php -r "xdebug_info();"
