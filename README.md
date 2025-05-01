## Installation

You can install the package via composer:

```bash
composer require squareconcepts/square-ui
```

### Add these to the page
 in the head tag
```html
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.0.0/ckeditor5.css" crossorigin>
```
and just above your `@vite(['resources/css/app.css', 'resources/js/app.js'])`
```html
<script src="https://cdn.ckeditor.com/ckeditor5/45.0.0/ckeditor5.umd.js" crossorigin></script>
<script src="https://cdn.ckeditor.com/ckeditor5/45.0.0/translations/nl.umd.js" crossorigin></script>
```

Also run
```bash
npm install sweetalert2
```

and add the following to your `app.js` file
```js
import Swal from 'sweetalert2'

window.Swal = Swal;
```



## Credits

-   [Square Concepts](https://github.com/squareconcepts)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

