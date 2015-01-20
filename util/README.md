# Setting up Github webhook deployments	
This code allows you to setup automatic deployments using github's webhook system. The following instructions were written for a static site where the HTML is committed to the repository and served on the staging or production servers. 

## How it works
The code is designed to work easily into your normal development process so that you can update a local or staging site often when pull-requests are merged. Deployment to the production server is controlled by "Releases", which allows you to tag versions of the site over time and include release notes. The normal workflow could look something like this:

* Pull-requests created from master branch and merged when tested.
* Merging pull-requests automatically updates your staging site for final QA. 
* When you are ready to deploy to production, you create a "Release".

## Instructions
1. Put <code>deploy.php</code> in the directory where your site HTML is and make sure it's accessible to the outside world by the URL "[your domain]/deploy.php".

2. Copy the <code>local-config-sample.php</code> file and rename it <code>local-config.php</code>. This file should be located outside your web root in the <code>util</code> directory. 

3. Setup a separate webhook for production and staging in the repository on github.com where your code is hosted. You will need to create a new webhook for each with a different URL endpoint and trigger.

	* Select "Settings > Webhooks & Services".
	* Select "Add webhook".
	* Enter the "Payload URL" as <code>https://[domain]/deploy.php</code>
	* Select "Content type" as <code>application/x-www-form-urlencoded</code>
	* Enter a "Secret". This should be a random string with high entropy.
	* Select "Which events would you like to trigger this webhook?" as <code>Let me select individual events</code>.
	* Select <code>Push</code> or <code>Release</code> as triggers depending on if this is for staging or production respectively.
	* Select <code>Active</code> to enable this webhook.  

4. Change the values in the <code>local-config.php</code> file:

	* **repository:** The repository your code lives in.
	* **secretkey:** The secret key created in your webhook above.
	* **branch:** The branch you'll be pulling from.
	* **server:** The server this setup is for (production, staging, local).
	* **execute:** The commands that will be run to pull the code.
 
