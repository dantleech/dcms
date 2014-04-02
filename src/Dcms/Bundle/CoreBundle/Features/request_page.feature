Feature: Request a page
    In order to access content from a web browser
    As a user
    I want to be able to enter a web address and see the content at that address

    Background:
        Given there is a host "dantleech.com" for site "/dcms/sites/dantleech"
        Given there is a host "www.dantleech.com" for site "/dcms/sites/dantleech"
        And there is a node at "/dcms/sites/dantleech" of type "dcms:site"
        And there is a node at "/dcms/sites/dantleech/endpoints/homepage" of type "dcms:endpoint"
        And there is a node at "/dcms/sites/dantleech/endpoints/homepage/mental" of type "dcms:mental:page" with properties:
            | jcr:title         | My Homepage |
            | body          | Welcome to the jungle baby |

    Scenario: View test page
        Given I am on "http://dantleech.com"
        Then show last response
