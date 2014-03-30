Feature: Request a page
    In order to access content from a web browser
    As a user
    I want to be able to enter a web address and see the content at that address

    Background:
        Given there is a host "dantleech.com"
        And there is a "route" "homepage" with the following properties:
            | property_name | value |
            | mental | page |

    Scenario: View test page
