<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="public/css/styleBack.css" />
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea',
            toolbar: 'undo redo | styleselect | bold italic backcolor| alignleft aligncenter alignright alignjustify |',
            menubar: false,
            width: 950});</script>
            <title><?= $title ?></title>
    </head>

    <body>
        <?= $content ?>
    </body>
</html>