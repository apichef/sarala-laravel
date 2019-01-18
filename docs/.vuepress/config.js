module.exports = {
    base: '/sarala-laravel-docs/',
    title: 'Sarala Laravel',
    description: '{json:api} Laravel Companion',
    head: [
        ['link', { rel: 'icon', href: '/images/sarala-logo.png' }]
    ],
    themeConfig: {
        logo: '/images/sarala-logo.png',

        nav: [
            { text: 'API', link: '/guide/' },
            { text: 'Packagist', link: 'https://packagist.org/packages/sarala-io/sarala' },
            { text: 'Creator', link: 'https://milroy.me' },
        ],

        sidebar: {
            '/guide/': [
                ['/guide/', 'Installation'],
                ['/guide/what-it-does.md', 'What it does'],
                ['/guide/middleware.md', 'Middleware'],
                ['/guide/request.md', 'Request'],
                ['/guide/query-builder.md', 'Query Builder'],
                ['/guide/include-helpers.md', 'Include Helpers'],
                ['/guide/query-param-bag.md', 'QueryParamBag'],
                ['/guide/controller.md', 'Controller'],
                ['/guide/transformer.md', 'Transformer'],
                ['/guide/links.md', 'Links'],
                ['/guide/link.md', 'Link'],
                ['/guide/exception.md', 'Exception'],
            ]
        },

        lastUpdated: 'Last Updated',
        repo: 'sarala-io/sarala-laravel',
        repoLabel: 'GitHub',
        docsRepo: 'sarala-io/sarala-laravel-docs',
        docsDir: 'docs',
        docsBranch: 'master',
        editLinks: true,
        editLinkText: 'Help us improve this page!'
    }
}
