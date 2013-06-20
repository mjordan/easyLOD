<?php
/**
 * Sample tests of the FOAF data source plugin using SimpleTest
 * (http://simpletest.org/). Note: SimpleTest library is not distributed
 * with easyLOD.
 */

require_once('/path/to/simpletest/autorun.php');
require_once('/path/to/simpletest/web_tester.php');
SimpleTest::prefer(new TextReporter());

class FOAFTests extends WebTestCase {
  // Tests for requesting and receiving RDF.
  function testGetRDF() {
    $this->addHeader('Accept: application/rdf+xml');

    $this->get('http://localhost/easyLOD/resource/foaf:random@modnar44.com');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'application/rdf+xml');

    $this->get('http://localhost/easyLOD/resource/foaf:sexyguy@iamvain.info');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'application/rdf+xml');

    $this->get('http://localhost/easyLOD/resource/foaf:give_me_your_credit_card_6@yahoo.com');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'application/rdf+xml');
  }

  // Tests for requesting and receiving HTML.
  function testGetHTML() {
    $this->get('http://localhost/easyLOD/resource/foaf:random@modnar44.com');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'text/html');

    $this->get('http://localhost/easyLOD/resource/foaf:sexyguy@iamvain.info');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'text/html');

    $this->get('http://localhost/easyLOD/resource/foaf:give_me_your_credit_card_6@yahoo.com');
    $this->assertResponse(200);
    $this->assertHeader('Content-Type', 'text/html');
  }

  // Tests for returning 404 responses.
  function test404() {
    // Test invalid id.
    $this->get('http://localhost/easyLOD/resource/foaf:xxxx');
    $this->assertResponse(404);

    // Test invalid namespace.
    $this->addHeader('Accept: application/rdf+xml');
    $this->get('http://localhost/easyLOD/resource/invalidnamespace:sexyguy@iamvain.info');
    $this->assertResponse(404);

    // Test invalid query.
    $this->get('http://localhost/invalidpath/resource/foaf:give_me_your_credit_card_6@yahoo.com');
    $this->assertResponse(404);
  }
}
?>
