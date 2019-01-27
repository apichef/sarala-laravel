---
sidebarDepth: 0
---

## Sarala

Sarala is a opinionated Laravel companion package, enables you to build truly RESTful APIs which consumers will love using. 

### Content Negotiation Support

It is seamless. Sarala is intelligent enough to validate headers and response according client request. And it is easy to introduce new media types to your API. Simple implement your [custom serializers](https://fractal.thephpleague.com/serializers/#custom-serializers) and add it to [handlers](/guide/installation.md).

### Easy Query Building

Sarala provides a clean way to translate complex API requests to Eloquent query by introducing [Request Query Builders](/guide/query-builder.md). Query builder [helper methods](/guide/include-helpers.md) will make it even easier while Sarala [QueryParamBag](/guide/query-param-bag.md) give you a simple api to read complex request query strings.

### Security Focus

Laravel already protects us from OWASP issues. But as we enable to include related resources via API requests, those includes must go through a check.

Sarala [Request](/guide/request.me) provides a simple way to sanitizing includes.

### Performance Focus

Just by using [Sarala Query Builders](/guide/query-builder.md) you are protected against making N+1 queries while transforming data. Yes it is a huge win!

[ETag]() allows caches to be more efficient, and saves bandwidth, as a web server does not need to send a full response if the content has not changed.

### HATEOAS Focus

[Hypermedia Controls](https://martinfowler.com/articles/richardsonMaturityModel.html) is the most important fact that your API must have to become a truly RESTful API. [Links](/guide/links.me) and [Link](/guide/link.me) will help you with that.   
