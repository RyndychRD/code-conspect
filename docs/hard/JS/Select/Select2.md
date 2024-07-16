Удобная библиотека для select, чтобы отображалось красиво и всякие штуки поддерживало

[Ссылка на сорц](https://select2.org/selections)

Пример инициализации для существующего select

```js
     $('.defaultLang_select2').select2({
            width: 300,
            dropdownAutoWidth: true,
            placeholder: '',
            theme: 'bootstrap-5',
            matcher: matchTrimNoCase, //фунция для фильтрации значения при поиске
        })
        
        function matchTrimNoCase(params, data) {
            if ($.trim(params.term) === "") {
                return data;
            }
            if (typeof data.text === "undefined") {
                return null;
            }
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return (modifiedData = $.extend({}, data, true));
            }
            return null;
        }
```