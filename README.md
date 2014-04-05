Dissonant CMS
=============

The dissonant CMS is a *fully dynamic*, *hierachical* CMS which leverages the
native power of PHPCR with a ...

Principles:

- **Cascading resource resolution**: Sites can be nested, each site can define
  its own resources. When a resource is missing it will try the parent site
  and so on.
- **No native administrative interface**: Because of the nature of PHPCR, all
  administration *can* be done via the PHPCR shell. Administration interfaces
  can, however, be created or imported from within the shell.
- **All potentially mutable data is a resource**: This includes templates and
  configuration. All resources are stored in PHPCR.

Why?
----

- No need to redeploy the website everytime your boss asks you to make a
  change, as much as possible is fully modifiable at runtime.
