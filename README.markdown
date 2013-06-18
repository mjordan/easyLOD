# easyLOD

easyLOD exposes arbitrary content as Linked Open Data. This data could come from a relational database, from a set of static files, or from an external API available over the web. Each source of data has its own plugin.

easyLOD's goal is to make it as simple to publish Linked Data while incorporating as many Linked Open Data best practices as possible. For more information on publishing Linked Data, consult Tom Heath and Christian Bizer's Linked Data: Evolving the Web into a Global Data Space (http://linkeddatabook.com/editions/1.0/).

## Features

* No dependencies other than PHP 5.3 or higher.
* Written in Slim, a PHP micro-framework (included in the easyLOD distribution). 
* Performs content negotiation to allow Linked Data browsers to request RDF-encoded data.
* Plugins for data sources are easy to write (more info is provided below).
* Gets data from sources in realtime, not by writing out representations to files.
* Resource URIs use 'namespaces' to identify data sources, so when data source back end changes, URIs don't need to (more info is provided below).

## Resource URIs

Everything on the Web of Data must have a unique URI. HTTP URIs have a server name and a path, and within a given server name, the unique parts of the URI are expressed in its path. If organizations assign unique identifiers to the things it describes, these identifers can be used as the unique parts of URIs.

easyLOD imposes a specific pattern for URIs, namely, the string 'resource', a string (the 'namespace'), then a colon (':'), then an ID. The combination of namespace and ID must be unique. For example, a URI managed by easyLOD looks like this:

http://myorg.edu/pathtoeasylod/resource/namespace3:foo

The ID (in the case of the example above, 'foo') can be any string of characters that are valid in URIs. easyLOD doesn't assing the IDs -- that's up to you. The namespace (in this case, 'namespace3') maps to a data source plugin (but see below for how to create explicit mappings from namespaces to plugins). The unique combination of namespace and ID tell easyLOD which data source plugin to use, and then which item managed by that plugin the request is for.

## Data source plugins

easyLOD uses plugins to retrieve data, which it then wraps in RDF/XML to send to the Linked Data browser. If the browser making the request is not a Linked Data browser (i.e., probably a human using Chrome, Firefox, etc.), the data is wrapped in HTML and sent to the browser. Plugins can also redirect users to external websites that describe the item corresponding to the identifier.

Four plugins are provided with easyLOD: 

* a plugin that retrieves Dublin Core metadata from CONTENTdm (http://contentdm.org/), which provides a web-services API
* a plugin that gets FOAF data from a small CSV file (included in the plugin directory)
* a plugin that retrieves Dublin Core metadata describing books from a MySQL database (SQL file is included in the plugin directory)
* a plugin that serves static RDF files transformed from MODS descriptions for items in an Islandora (http://islandora.ca/) repository

These are intended to illustrate how information can be retieved from different data sources. 

## Mapping namespaces in resource URIs to data source plugins

There are two ways to tell EasyLOD which namespaces should map to which data source plugins: 1) direct mappings and 2) configured mappings. In a direct mapping, the URI's namespace is identical to the plugin name; in a configured mapping, an entry in a file titled 'plugins.php' indicates which namespaces invoke which plugins. Both approaches can work at the same time, although if there is a conflict, the configured mapping wins.

If your the namespaces in your resource URIs map one-to-one to your data source plugins, you do not need a plugins.php file.

Configured mappings allow more flexibility in choosing your namespaces, and let you reuse the same plugin for serving up Linked Data for resource URIs containing heterogeneous namespaces. EasyLOD ships with an example plugins.php file that illustrates configured mappings. The file contains an associative array $plugins that has members representing namespaces; each of these is also an associative array that defines which data source plugin it to be used and also an optional 'dataSourceConfg' associative array that overrides the plugin's config settings as defined in its dataSourceConfig() function:

```php
$plugins = array(
  // Configuration for URIs using the 'foo' namespace.
  'foo' => array(
      'plugin' => 'foaf',
      'dataSourceConfig' => array(
        'input_file' => 'data_sources/foaf/people_foo.csv',
      ),
  ),
  // Configuration for URIs using the 'bar' namespace.
  'bar' => array(
      'plugin' => 'foaf',
      'dataSourceConfig' => array(
        'input_file' => 'data_sources/foaf/people_bar.csv',
      ),
  ),
);
```

All of a plugin's settings must be included in the entries in plugins.php.

## Installation and testing

Unzip the distribution, put it somewhere in your web server's document root, and then use the following command to test the FOAF plugin (this command assumes you are issuing it on the same server where you installed easyLOD):

`curl -L -H 'Accept: application/rdf+xml' http://localhost/easyLOD/resource/foaf:random@modnar44.com`

You should see the following:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:foaf="http://xmlns.com/foaf/0.1/">
 <rdf:Description rdf:about="http://localhost/easyLOD/resource/foaf:random@modnar44.com">
  <foaf:mbox>random@modnar44.com</foaf:mbox>
  <foaf:surname>Jalfrezi</foaf:surname>
  <foaf:givenName>Large</foaf:givenName>
  <foaf:page>http://jalfrezitogo.edu</foaf:page>
 </rdf:Description>
</rdf:RDF>
```

Open a graphical web browser and go to the same URL. You should see a simple web page with this content:

> Large Jalfrezi
>
> mbox: random@modnar44.com
>
> surname: Jalfrezi
>
> givenName: Large
>
> page: http://jalfrezitogo.edu

The two different responses to the same URI illustrate the Linked Data concept of content negotiation: browsers that request RDF, which is what Linked Data browsers do, receive your data in RDF, while ordinary web browsers receive your data in a format that is more readable by humans.

## No SPARQL endpoint

easyLOD does not provide a mechanism for querying the Linked Data it exposes. Many Linked Data providers supply a SPARQL endpoint to allow queries. One strategy for providing a SPARQL endpoint for easyLOD would be to:

1. Configure easyLOD to expose data using a plugin.
2. Write a script to iterate through all of the identifiers in your data and send corresponding queries to their easyLOD resource URLs.
3. Have the script write out the RDF/XML representations of your resources as static files.
4. Install and configure the Mulgara RDF Database (http://www.mulgara.org/) or some other RDF triple store that provides its own SPARQL endpoint.
5. Load your data as RDF triples.
6. Expose the databases's SPARQL endpoint for Linked Data applications to query.
