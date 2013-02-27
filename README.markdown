# easyLOD

easyLOD exposes arbitrary content as Linked Open Data. This data could come from a relational database, from a set of static files, or from an external API available over the web. Each source of data has its own plugin.

easyLOD's goal is to make it as simple to publish Linked Data while incorporating as many Linked Open Data best practices as possible. For more information on publishing Linked Data, consult Tom Heath and Christian Bizer's Linked Data: Evolving the Web into a Global Data Space (http://linkeddatabook.com/editions/1.0/).

## Features

* No dependencies other than PHP 5.3 or higher.
* Content negotiation to accomodate both Linked Data browsers and ordinary browsers.
* Written in Slim, a PHP micro-framework (included in the easyLOD distribution). 
* Plugins for data sources are easy to write (more info is provided below).
* Gets data from sources in realtime, not by writing out different representations to files.
* Resource URIs use 'namespaces' to identify data sources, so when data source back end changes, URIs don't need to (more info is provided below).

## Resource URIs

Everything on the Web of Data a must have a unique URI. HTTP URIs have a server name and a path, and within a given server name, the unique parts of the URI are expressed in its path. If organizations assign unique identifiers to the things it describes, these identifers can be used as the unique parts of URIs.

easyLOD imposes a specific pattern for URIs, namely, a string (the 'namespace'), then a color (':'), then a unique ID. For example, a URI managed by easyLOD looks like this:

http://myorg.edu/pathtoeasylod/namespace1:foo

The unique ID (in the case of the example above, 'foo') can be any string of characters that are valid in URIs. easyLOD doesn't assing the IDs -- that's up to you. The namespace (in this case, 'namespace1') maps to a data source, which will have its own plugin. The unique combination of namespace and ID tell easyLOD which data source plugin to use, and then which item managed by that plugin the request is for.

## Data source plugins

easyLOD uses plugins to retrieve data, which it then wraps in RDF/XML to send to the Linked Data browser. If the browser making the request is not a Linked Data browser (i.e., probably a human using Chrome, Firefox, etc.), the data is wrapped in HTML and sent to the browser.

Three plugins are provided with easyLOD, one to get Dublin Core data from CONTENTdm (which has a web-services API), one to get FOAF (http://xmlns.com/foaf/spec/) data from a small CSV file, and one to get simple data describing books written by Philip K. Dick from a MySQL database. All are in the 'data_sources' directory in the easyLOD distribution.

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

