# Tasks & Responsibilities #

## Chris ##

* Database Setup & Design
* GIT Merges and Main branches (Master and Dev)
* Placement Action Page
* Placement Report
* Pull list
* Pull Report
* Overview Report
## Adam ##
* README
* Bug tracker
* Landing Page
* Moves Action Page
* Move Report Page
* Rolloff Action Page



# Summary #
   * A.R.R.G. stands for Asset Refresh Report Generator.  This web application will handle the process of the Refresh of Assets in our school district environment.  This app is intended to be used onsite with a hand held or laptop type computer to access and store and report on asset placement, disposal, movement, and staging during the end of life cycle of certain machines.

* Action Pages- Used to scan target assets into ARRG into their respective pages.  Placement for placement items, moves for move items, Rolloff for rolloff items, Pull for pull items and Staging for staging items (This page is not yet determined).

* Report Pages- Used to report back on the status of said action pages.  Will be used to export report lists to desired format, at this point only .xls and .csv will be supported unless .pdf is not complex to implement.

# Workflow #
- navigate to landing page http://ip.ip.ip.ip:80/dev/ARRG/index.php
- select action page or report page based on task
- input information needed into boxes
- scan assets to apply that information to the scanned assets
- move on to next task
- once all tasks have been completed, generate report if needed to verify
- exit ARRG while saying "ARRG"


* Version - 1.0
* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)

# Configuration #

* Summary of set up None
* Configuration - None
* Dependencies - LAMP
* Database configuration - PHPMYAdmin - MySQL
* How to run tests - 
  Tests will be run with report generation, data input, data export
* Deployment instructions - 
  Install on web server at Shoal, use it to run the web app on target device

# Contribution guidelines #

* Writing tests
* Tests should include base cases for most.
    1. One Asset tag per return 
2. Certain string length (7 characters)
3. No special characters
4. Correct data types for fields checked for
* Tests should conclude with extraneous data and circumstances
    1. No connection to server
2. Bad or invalid data input (NULL)
* Code review
* Other guidelines
* Other community or team contact
