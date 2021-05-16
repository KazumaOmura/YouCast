# MAMP-DBの同期方法
```
cd /applications/mamp/db/mysql57
※「Slack - アプリ開発」であがるyoucastフォルダを解凍し移動後の階層にドラッグ&ドロップして上書き
```

# 開発におけるGitの操作方法
作業ディレクトリへの移動 & 作成
```
cd /applicaiotns/mamp/htdocs
mkdir youcast
cd youcast
```

git環境構築
```
git init
```

最初のgit操作
```
git clone https://github.com/KazumaOmura/youcast.git //リモートブランチのクローンをローカルに作成
git branch //branch確認
git branch "#" //branch作成
git checkout "#" //branch変更
```

2回目以降のgit操作
```
git checkout master //branch変更
git pull origin master //リモートブランチとローカルブランチの変更を合わせる
git checkout "#" //branch変更
git status //ローカルブランチとリモートブランチのファイル比較
git add "#" //gitにpushするファイルの選択
git commit -m "#" //コミット名作成
git push -f origin "#" //ブランチ名選択によるpush
git checkout "#" //branch変更
```
