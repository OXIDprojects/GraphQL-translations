# GraphQL-translations

## oxhackathon19

![Alt text](out/pictures/screenshot.png?raw=true "GraphqQL-Translations")

This module provides the possibility to change translations
in the shop using GraphQL. There are two query endpoints defined
and one mutation endpoint.

You can query the existing locales in the shop and then for each
locale a list of translations. And you can send back a translation
that then will be set in the shop. There is also a mutation provided
to reset the translations changed through this API.

You can use it together with the React module for the admin side of the shop [react-translations](https://github.com/OXIDprojects/React-translations)

To use this module, the following prerequisites exist: Your shop
version must be at least 6.5.0 And you need the "oxid-esales/graphql-base": "^1.0.0"
installed.
