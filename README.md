# Lunchbox

Turn a MODX folder into a paginated container to help organize your site and reduce page load times in the manager. 



---------------------------------

## Installation

You can install Lunchbox via the standard MODx package manager, or you can install Lunchbox via Repoman:

1. Clone the Lunchbox repository from https://github.com/craftsmancoding/lunchbox to a dedicated directory inside your MODx web root, e.g. "mypackages"
2. Run "composer install" on your new repository to pull in the package dependencies.
3. Run the command-line repoman tool on the lunchbox/ directory, e.g. "php repoman install /home/myuser/public_html/mypackages/lunchbox"


## Use

Once you have Lunchbox installed, find a folder on your site that contains a lot of pages.  
Edit the page and head to the "Settings" tab.
In the "Resource Type" dropdown, select "Lunchbox" and save.
Your folder should now be converted to a "Lunchbox": its contents will no longer open up in the 
MODX resource tree and instead you can navigate a paginated list of resources.