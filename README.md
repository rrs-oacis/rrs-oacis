# rrs-oacis

ADFとOACISのソフト

## ビルド
ライブラリの管理にcomposerを使用しています。


ライブラリのインストール
```
$ ./setup.sh
```

サーバーの起動
```
$ ./server.sh
```

## ビルド(Docker)

ImageFileを作成
```
$ ./docker_build.sh
```

ImageFileからサーバーを起動
```
$ ./docker_run.sh
```

サーバーの起動(Docker内で使用)
```
$ ./docker_php_server.sh
```

## テスト

ポート番号は6040で起動します

## コントリビューター
コミットメッセージは自由です。


コミットは出来るだけ小さく、プルリクエストは機能単位でしてください。
