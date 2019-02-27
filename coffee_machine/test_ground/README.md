#### Базовые кейсы
##### Продукты:
<pre>
<code>curl -d "name=apple" "http://127.0.0.1:8000/product"</code> - добавить один
<code>curl "http://127.0.0.1:8000/product/1"</code> - получить один
<code>curl "http://127.0.0.1:8000/product"</code> - получить все
<code>curl -d "name=testertesterov&active=0" "http://127.0.0.1:8000/product/put/1"</code> - обновить один
<code>curl "http://127.0.0.1:8000/product/put/1?name=testertesterov"</code> - обновить один
<code>curl -X DELETE "http://127.0.0.1:8000/product/1"</code> - удалить один
</pre>
##### Цены:
<pre>
<code>curl -d "cost=100" "http://127.0.0.1:8000/price"</code> - добавить один
<code>curl "http://127.0.0.1:8000/price/1"</code> - получить один
<code>curl "http://127.0.0.1:8000/price"</code> - получить все
<code>curl -d "cost=100500&active=0" "http://127.0.0.1:8000/price/put/1"</code> - обновить один
<code>curl http://127.0.0.1:8000/price/put/1?cost=100500"</code> - обновить один
<code>curl -X DELETE "http://127.0.0.1:8000/price/1"</code> - удалить один
</pre>
##### Банкноты:
<pre>
<code>curl -d "name=RUB" "http://127.0.0.1:8000/banknote"</code> - добавить один
<code>curl "http://127.0.0.1:8000/banknote/1"</code> - получить один
<code>curl "http://127.0.0.1:8000/banknote"</code> - получить все
<code>curl -d "name=RUB&active=1" "http://127.0.0.1:8000/banknote/put/1"</code> - обновить один
<code>curl http://127.0.0.1:8000/banknote/put/1?name=RUB&&active=1"</code> - обновить один
<code>curl -X DELETE "http://127.0.0.1:8000/banknote/1"</code> - удалить один
</pre>
##### Архитектура продуктов:
<pre>
<code>curl -d "product_id=1&price_id=1&banknote_id=1" "http://127.0.0.1:8000/communication"</code> - добавить один
<code>curl "http://127.0.0.1:8000/communication/1"</code> - получить один
<code>curl "http://127.0.0.1:8000/communication"</code> - получить все
<code>curl -d "product_id=1&price_id=1&banknote_id=1" "http://127.0.0.1:8000/communication/put/1"</code> - обновить один
<code>curl "http://127.0.0.1:8000/communication/put/1?product_id=1&&price_id=1&&banknote_id=1"</code> - обновить один
<code>curl -X DELETE "http://127.0.0.1:8000/communication/1"</code> - удалить один
</pre>
##### Кофе-машина:
<pre>
<code>curl -d "contribution=1&&banknote_id=1&&product_communication_id=1" "http://127.0.0.1:8000/payment"</code> - купить один
</pre>
