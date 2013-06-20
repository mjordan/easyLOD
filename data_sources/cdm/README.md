To use this plugin:

1. Unzip https://github.com/mjordan/easyLOD/archive/master.zip and put the easyLOD directory in the webroot of a server running PHP 5.3
2. Edit data_sources/cdm/cdm.php to use your CONTENTdm server
3. Get the alias and pointer of an item in CONTENTdm that has some Dublin Core fields (e.g., in the case of http://content.lib.sfu.ca/cdm/compoundobject/collection/bcp/id/15068, the alias is 'bcp' and the pointer is '15068')
4. The URI for this item in easyLOD is: e.g., http://path/to/easylod/resource/cdm:bcp:15068
5. To see the RDF representation for this item, issue the command curl -L -H 'Accept: application/rdf+xml'  http://path/to/easylod/resource/cdm:bcp:15068 (replacing the alias and pointer from one of your own collections)
6. To see the human-readable version of this metadata, point your graphical web browser at the same URL. easyLOD will redirect you to the CONTENTdm page for the item.
