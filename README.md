# GraphQL-translations
## oxhackathon19

This module provides the possibility to change translations
in the shop using GraphQL. There are two query endpoints defined
and one mutation endpoint.

You can query the existing locales in the shop and then for each
locale a list of translations. And you can send back a translation
that then will be set in the shop. There is also a mutation provided
to reset the translations changed through this API.

To use this module, the following prerequisites exist: Your shop
version must be at least 6.2. And you need the graph-ql-base-module
installed. Both are not yet released officially. For the shop use
the HEAD of the b-6.x branch in the public repository of oxideshop_ce.
For the graph-ql-base-module contact productmanagement@oxid-esales.com
to give you access to the repository that currently is still not
publicly accessible.