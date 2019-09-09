---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://tax.ian.ph/docs/collection.json)

<!-- END_INFO -->

#Country management


API's for managing the country records
<!-- START_c7fae8a06934c43d9aac54f559ea8bee -->
## Display a listing of the countries table from the database.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/country?page=1" 
```

```javascript
const url = new URL("https://tax.ian.ph/api/country");

    let params = {
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
            "country_code": "USA",
            "country_name": "United States",
            "currency_code": "USD",
            "computation_type": 1
        }
    ],
    "links": {
        "first": "\/country?page=1",
        "last": "\/country?page=324",
        "prev": "\/country?page=1",
        "next": "\/country?page=3"
    },
    "meta": {
        "current_page": 2,
        "from": 11,
        "last_page": 324,
        "path": "\/country",
        "per_page": 10,
        "to": 20,
        "total": 3232
    }
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/country`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | int Used for pagination, indicates the current page of the list of record.

<!-- END_c7fae8a06934c43d9aac54f559ea8bee -->

<!-- START_ad737d96b4511e210e932bbb8c8a5b05 -->
## Store a newly created country in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/country" \
    -H "Content-Type: application/json" \
    -d '{"country_name":"United States","country_code":"USA","currency_code":"USD","computation_type":1}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/country");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_name": "United States",
    "country_code": "USA",
    "currency_code": "USD",
    "computation_type": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_name": [
                "Country name is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/country`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_name | string |  required  | The name of the country.
    country_code | string |  optional  | The country's iso 3 code.
    currency_code | string |  optional  | The country's iso 3 currency code.
    computation_type | integer |  required  | The type of tax computation the country is using.

<!-- END_ad737d96b4511e210e932bbb8c8a5b05 -->

<!-- START_460af50c7eabd663a275b437318f78c9 -->
## Update the specified country in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT "https://tax.ian.ph/api/country/1?uuid=7b7009a8-cf1b-4466-84a1-8051b34a58b2" \
    -H "Content-Type: application/json" \
    -d '{"country_name":"United States","country_code":"USA","currency_code":"USD","computation_type":1}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/country/1");

    let params = {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_name": "United States",
    "country_code": "USA",
    "currency_code": "USD",
    "computation_type": 1
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_name": [
                "Country name is required"
            ]
        }
    ],
    "message": "Request validation failed"
}
```

### HTTP Request
`PUT api/country/{country}`

`PATCH api/country/{country}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_name | string |  required  | The name of the country.
    country_code | string |  optional  | The country's iso 3 code.
    currency_code | string |  optional  | The country's iso 3 currency code.
    computation_type | integer |  required  | The type of tax computation the country is using.
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    uuid |  optional  | string The uuid of the country to be updated.

<!-- END_460af50c7eabd663a275b437318f78c9 -->

<!-- START_957c718b964b96a83b1d966592818a9a -->
## Deletes a country in the storage

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://tax.ian.ph/api/country/1" \
    -H "Content-Type: application/json" \
    -d '{"uuid":"7b7009a8-cf1b-4466-84a1-8051b34a58b2"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/country/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2"
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": "Country does not exists",
    "message": "Request validation failed"
}
```

### HTTP Request
`DELETE api/country/{country}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    uuid | string |  required  | The uuid of the country to be deleted.

<!-- END_957c718b964b96a83b1d966592818a9a -->

#County management


API's for managing the country records
<!-- START_1170cdf460cb689713835e78b0d55400 -->
## Display a listing of the counties table from the database.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/county?page=1" 
```

```javascript
const url = new URL("https://tax.ian.ph/api/county");

    let params = {
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/county`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | int Used for pagination, indicates the current page of the list of record.

<!-- END_1170cdf460cb689713835e78b0d55400 -->

<!-- START_0a9d072b4530efecf0655c68fbbbbc7c -->
## Store a newly created county in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/county" \
    -H "Content-Type: application/json" \
    -d '{"county_name":"Nevada","county_code":"LSVD","country_code":"USA","state_code":"LASVEGAS"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/county");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "county_name": "Nevada",
    "county_code": "LSVD",
    "country_code": "USA",
    "state_code": "LASVEGAS"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "message": "Request validation failed",
    "errors": [
        {
            "county_name": [
                "County name is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/county`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    county_name | string |  required  | The name of the county.
    county_code | string |  optional  | The county's unique code.
    country_code | string |  optional  | The county's parent country iso 3 code.
    state_code | string |  required  | The county's parent state iso code.

<!-- END_0a9d072b4530efecf0655c68fbbbbc7c -->

<!-- START_fd182c95342ffafe63ac52105d6205f8 -->
## Update an existing created county in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT "https://tax.ian.ph/api/county/1?uuid=7b7009a8-cf1b-4466-84a1-8051b34a58b2" \
    -H "Content-Type: application/json" \
    -d '{"county_name":"Nevada","county_code":"LSVD","country_code":"USA","state_code":"LASVEGAS"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/county/1");

    let params = {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "county_name": "Nevada",
    "county_code": "LSVD",
    "country_code": "USA",
    "state_code": "LASVEGAS"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_name": [
                "Country name is required"
            ]
        }
    ]
}
```

### HTTP Request
`PUT api/county/{county}`

`PATCH api/county/{county}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    county_name | string |  required  | The name of the county.
    county_code | string |  optional  | The county's unique code.
    country_code | string |  optional  | The county's parent country iso 3 code.
    state_code | string |  required  | The county's parent state iso code.
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    uuid |  optional  | string The uuid of the county to be updated.

<!-- END_fd182c95342ffafe63ac52105d6205f8 -->

<!-- START_7ad2482efac1df2982119be14f2bceae -->
## Deletes a country in the storage

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://tax.ian.ph/api/county/1" \
    -H "Content-Type: application/json" \
    -d '{"uuid":"7b7009a8-cf1b-4466-84a1-8051b34a58b2"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/county/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2"
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": "County does not exists",
    "message": "Request validation failed"
}
```

### HTTP Request
`DELETE api/county/{county}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    uuid | string |  required  | The uuid of the county to be deleted.

<!-- END_7ad2482efac1df2982119be14f2bceae -->

<!-- START_3cd706daa450e09c9ae77680d35338fc -->
## List all the counties using a state code as parameter

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/county/list_by_state_code/1" \
    -H "Content-Type: application/json" \
    -d '{"state_code":"LVND"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/county/list_by_state_code/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "state_code": "LVND"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "county_code": "NVD",
        "county_name": "Nevada"
    }
]
```
> Example response (422):

```json
{
    "success": false,
    "errors": "State does not exists",
    "message": "Request validation failed"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/county/list_by_state_code/{state_code}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    state_code | string |  required  | The state code of the county's parent state.

<!-- END_3cd706daa450e09c9ae77680d35338fc -->

#State management


API's for managing the country records
<!-- START_2cec8202422401ca0c9d938f4f8d36ac -->
## Display a listing of the states table from the database.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/state?page=1" 
```

```javascript
const url = new URL("https://tax.ian.ph/api/state");

    let params = {
            "page": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/state`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | int Used for pagination, indicates the current page of the list of record.

<!-- END_2cec8202422401ca0c9d938f4f8d36ac -->

<!-- START_64086d806793c3f71324c996f84063cc -->
## Store a newly created state in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/state" \
    -H "Content-Type: application/json" \
    -d '{"state_name":"Las Vegas","state_code":"LS","country_code":"USA"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/state");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "state_name": "Las Vegas",
    "state_code": "LS",
    "country_code": "USA"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "state_name": [
                "State name is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/state`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    state_name | string |  required  | The name of the state.
    state_code | string |  optional  | The county's unique code.
    country_code | string |  optional  | The county's parent country iso 3 code.

<!-- END_64086d806793c3f71324c996f84063cc -->

<!-- START_04960501645981db491ce2269b10a0f0 -->
## Updates a newly created state in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT "https://tax.ian.ph/api/state/1?uuid=7b7009a8-cf1b-4466-84a1-8051b34a58b2" \
    -H "Content-Type: application/json" \
    -d '{"state_name":"Las Vegas","state_code":"LS","country_code":"USA"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/state/1");

    let params = {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "state_name": "Las Vegas",
    "state_code": "LS",
    "country_code": "USA"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "state_name": [
                "State name is required"
            ]
        }
    ]
}
```

### HTTP Request
`PUT api/state/{state}`

`PATCH api/state/{state}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    state_name | string |  required  | The name of the state.
    state_code | string |  optional  | The county's unique code.
    country_code | string |  optional  | The county's parent country iso 3 code.
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    uuid |  optional  | string The uuid of the state to be updated.

<!-- END_04960501645981db491ce2269b10a0f0 -->

<!-- START_c67f629cea631b19f491fa34e418ab9d -->
## Deletes a state in the storage

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://tax.ian.ph/api/state/1" \
    -H "Content-Type: application/json" \
    -d '{"uuid":"7b7009a8-cf1b-4466-84a1-8051b34a58b2"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/state/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2"
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": "State does not exists",
    "message": "Request validation failed"
}
```

### HTTP Request
`DELETE api/state/{state}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    uuid | string |  required  | The uuid of the state to be deleted.

<!-- END_c67f629cea631b19f491fa34e418ab9d -->

<!-- START_aa05483b2fbe3adc5f34cff3698caf06 -->
## List all the states using a country code as parameter

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/state/list_by_country_code/1" \
    -H "Content-Type: application/json" \
    -d '{"country_code":"USD"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/state/list_by_country_code/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_code": "USD"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "county_code": "LVN",
        "county_name": "Las Vegas"
    }
]
```
> Example response (422):

```json
{
    "success": false,
    "errors": "Country does not exists",
    "message": "Request validation failed"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/state/list_by_country_code/{country_code}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_code | string |  required  | The country code of the county's parent country.

<!-- END_aa05483b2fbe3adc5f34cff3698caf06 -->

<!-- START_6b7ed5cd2da7e87cc5510dea800077e8 -->
## List all the states using a country uuid as parameter

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/state/list_by_uuid/1" \
    -H "Content-Type: application/json" \
    -d '{"country_uuid":"7b7009a8-cf1b-4466-84a1-8051b34a58b2"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/state/list_by_uuid/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "county_code": "LVN",
        "county_name": "Las Vegas"
    }
]
```
> Example response (422):

```json
{
    "success": false,
    "errors": "Country does not exists",
    "message": "Request validation failed"
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/state/list_by_uuid/{country_code}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_uuid | string |  required  | The country uuid of the county's parent country.

<!-- END_6b7ed5cd2da7e87cc5510dea800077e8 -->

#Tax Rates management


API's for managing the tax rates records
<!-- START_0f42d0012f8ada75c8716fe7e778ecc9 -->
## Display a listing of the tax_rates table from the database.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://tax.ian.ph/api/tax-rate" \
    -H "Content-Type: application/json" \
    -d '{"country":"laudantium","state":"ipsam"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country": "laudantium",
    "state": "ipsam"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
            "country": "United States",
            "state": "Las Vegas",
            "county": "N\/A",
            "income_bracket": "$0 - $1,500",
            "rate": "2%",
            "note": "Example note"
        }
    ],
    "links": {
        "first": "\/tax-rate?page=1",
        "last": "\/tax-rate?page=324",
        "prev": "\/tax-rate?page=1",
        "next": "\/tax-rate?page=3"
    },
    "meta": {
        "current_page": 2,
        "from": 11,
        "last_page": 324,
        "path": "\/tax-rate",
        "per_page": 10,
        "to": 20,
        "total": 3232
    }
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/tax-rate`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country | string |  optional  | The uuid of the country of the tax rate, to be used a filtering of the listing.
    state | string |  optional  | The uuid of the state of the tax rate, to be used a filtering of the listing.

<!-- END_0f42d0012f8ada75c8716fe7e778ecc9 -->

<!-- START_3bd1f395a849e71b41f29ac431e42cd4 -->
## Store a newly created tax rate in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/tax-rate" \
    -H "Content-Type: application/json" \
    -d '{"country_code":"USA","state_code":"LSV","county_code":"NVD","implementation_date":"architecto","rate_percentage":15.5,"rate_fixed":250.5,"bracket_minimum":23.375759725,"bracket_maximum":"corrupti","tax_type":17,"note":"voluptatem"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_code": "USA",
    "state_code": "LSV",
    "county_code": "NVD",
    "implementation_date": "architecto",
    "rate_percentage": 15.5,
    "rate_fixed": 250.5,
    "bracket_minimum": 23.375759725,
    "bracket_maximum": "corrupti",
    "tax_type": 17,
    "note": "voluptatem"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_code": [
                "country code is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/tax-rate`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_code | string |  required  | The country code for this tax rate.
    state_code | string |  optional  | The state code of this tax rate.
    county_code | string |  optional  | The county code of this tax rate.
    implementation_date | string |  required  | The date on which this tax rate will become effective.
    rate_percentage | float |  optional  | The rate is percentage.
    rate_fixed | float |  optional  | The rate in fixed amount.
    bracket_minimum | float |  required  | The minimum bracket for this tax rate.
    bracket_maximum | The |  optional  | maximum bracket for this tax rate
    tax_type | integer |  required  | The type of this tax, indicated 1 if single, 2 for joint.
    note | string |  required  | Descriptive note for this tax rate

<!-- END_3bd1f395a849e71b41f29ac431e42cd4 -->

<!-- START_c73aa5698c2526a03f7eb6fd71921f55 -->
## Store a newly created tax rate in storage.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT "https://tax.ian.ph/api/tax-rate/1?uuid=7b7009a8-cf1b-4466-84a1-8051b34a58b2" \
    -H "Content-Type: application/json" \
    -d '{"country_code":"USA","state_code":"LSV","county_code":"NVD","implementation_date":"modi","rate_percentage":15.5,"rate_fixed":250.5,"bracket_minimum":727.81741531,"bracket_maximum":"aperiam","tax_type":13,"note":"esse"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate/1");

    let params = {
            "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_code": "USA",
    "state_code": "LSV",
    "county_code": "NVD",
    "implementation_date": "modi",
    "rate_percentage": 15.5,
    "rate_fixed": 250.5,
    "bracket_minimum": 727.81741531,
    "bracket_maximum": "aperiam",
    "tax_type": 13,
    "note": "esse"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_code": [
                "country code is required"
            ]
        }
    ]
}
```

### HTTP Request
`PUT api/tax-rate/{tax_rate}`

`PATCH api/tax-rate/{tax_rate}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_code | string |  required  | The country code for this tax rate.
    state_code | string |  optional  | The state code of this tax rate.
    county_code | string |  optional  | The county code of this tax rate.
    implementation_date | string |  required  | The date on which this tax rate will become effective.
    rate_percentage | float |  optional  | The rate is percentage.
    rate_fixed | float |  optional  | The rate in fixed amount.
    bracket_minimum | float |  required  | The minimum bracket for this tax rate.
    bracket_maximum | The |  optional  | maximum bracket for this tax rate
    tax_type | integer |  required  | The type of this tax, indicated 1 if single, 2 for joint.
    note | string |  required  | Descriptive note for this tax rate
#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    uuid |  optional  | string The uuid of the tax rate to be updated.

<!-- END_c73aa5698c2526a03f7eb6fd71921f55 -->

<!-- START_427dc7fa91ce4f70278492ffb9e17385 -->
## Deletes a tax_rate in the storage

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://tax.ian.ph/api/tax-rate/1" \
    -H "Content-Type: application/json" \
    -d '{"uuid":"7b7009a8-cf1b-4466-84a1-8051b34a58b2"}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2"
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": "Tax rate does not exists",
    "message": "Request validation failed"
}
```

### HTTP Request
`DELETE api/tax-rate/{tax_rate}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    uuid | string |  required  | The uuid of the tax_rate to be deleted.

<!-- END_427dc7fa91ce4f70278492ffb9e17385 -->

<!-- START_4b45c613417c60dcb1d9a89cb8a1b7e7 -->
## Generate a easily readable preview of the tax rate

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/tax-rate/rate-preview" \
    -H "Content-Type: application/json" \
    -d '{"country_code":"autem","rate_percentage":3630.72,"rate_fixed":94371777.1}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate/rate-preview");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_code": "autem",
    "rate_percentage": 3630.72,
    "rate_fixed": 94371777.1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_code": [
                "country code is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/tax-rate/rate-preview`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_code | string |  required  | The country code and will be used as the reference for the currency
    rate_percentage | float |  optional  | The tax rate in percentage
    rate_fixed | float |  optional  | The tax rate in fixed amount

<!-- END_4b45c613417c60dcb1d9a89cb8a1b7e7 -->

<!-- START_d1b4b6959f04fbc333d50d2b5d2ea992 -->
## Generate a easily readable preview of the tax rate

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://tax.ian.ph/api/tax-rate/bracket-preview" \
    -H "Content-Type: application/json" \
    -d '{"country_code":"nihil","bracket_minimum":5671351.3276,"bracket_maximum":4684745.56}'

```

```javascript
const url = new URL("https://tax.ian.ph/api/tax-rate/bracket-preview");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "country_code": "nihil",
    "bracket_minimum": 5671351.3276,
    "bracket_maximum": 4684745.56
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true
}
```
> Example response (422):

```json
{
    "success": false,
    "errors": [
        {
            "country_code": [
                "country code is required"
            ]
        }
    ]
}
```

### HTTP Request
`POST api/tax-rate/bracket-preview`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    country_code | string |  required  | The country code and will be used as the reference for the currency
    bracket_minimum | float |  optional  | The minimum bracket of the tax rate
    bracket_maximum | float |  optional  | The maximum bracket of the tax rate

<!-- END_d1b4b6959f04fbc333d50d2b5d2ea992 -->


