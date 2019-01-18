---
sidebarDepth: 0
---

### What it does

##### Client makes a request →

```
GET /posts/8?include=tags,author,comments.author
Accept: application/vnd.api+json
```

##### 1 Middleware →

- Request header validation

##### 2 Request →

In addition to authorization and validation of request: 

- Sanitize includes

##### 3 QueryBuilder →

- Prepare query

##### 4 Controller →

- Inspecting request
- Prepare json:api serializer

##### 5 Transformer →

- Transform data
- Append action and relationship links

##### 6 Controller →

- Prepare response send back to client
