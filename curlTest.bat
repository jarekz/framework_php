::curl -i -H "Content-Type:application/json" -X PUT -d {\"fName\":\"imie\",\"lName\":\"naz\"} http://localhost/php/mvc/person

::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"160\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"161\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"162\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"163\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"164\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"165\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"166\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"167\"} http://localhost/php/mvc/person
::curl -i -H "Content-Type:application/json" -X DELETE -d {\"id\":\"168\"} http://localhost/php/mvc/person

curl -i -H "Content-Type:application/json" -X GET http://localhost/php/mvc/person