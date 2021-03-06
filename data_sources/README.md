# Using Data Source Plugins

The CSV plugin is probably the easiest to get going with since it requires no setup other than installing easyLOD on a web server.

# Plugin directories and files

A data source plugin is simply a PHP file that interfaces with your data. The file must be in a subdirectory of easyLOD's 'data_sources' directory and must contain have the same name as the subdirectory. For example:

`easylod\data_sources\myplugin\myplugin.php`

Any additional files that a plugin uses, such as XSL stylesheets, Slim templates, etc., should be within its directory as well. An obvious exceptions to this is any files that contain sensitive information like database usernames and passwords (files containing this type of information should go outside your web server's web root).

# Required functions

All plugins must contain the following functions:

* dataSourceConfig()
* getDataSourceNamespaces()
* getWebPage()
* getResourceData() 
* getResourceDataRaw() (if serving up raw RDF)

Documentation on these functions is available in the sample plugins.

