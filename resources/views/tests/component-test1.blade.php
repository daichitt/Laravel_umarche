<!-- resources\views\components\Tests\app.blade.php -->
<!-- このファイルを読み込みしている -->
<x-tests.app>
    <x-slot name="header">ヘッダー1</x-slot>
コンポーネントテスト1

<x-tests.card title="タイトル" content="コンテント" :message="$message" />
<x-tests.card title="タイトル2" />
</x-tests.app>
