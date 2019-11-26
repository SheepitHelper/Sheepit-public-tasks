# Dropbox Uploader

## Using Dropbox API

Uses [Dropbox API v2](https://www.dropbox.com/developers/documentation/http/documentation)'s selected features to perform.
Since the API appeared new successor, it may be stable for next few years. Their [API Explorer](https://dropbox.github.io/dropbox-api-v2-explorer/) is here for your experimentation.


## Usage Examples

### Upload a file via command line

    php -f uploader_cli.php <sourcefile> <target_dir> <token>
    
**target_dir has to begin with a "/"**
    
### Uploading a file

    $file = new uploader("/tmp/my-backup.pdf");
    $status = $db->upload($file);


### Fetching list of files

    $files = $db->files(); // including folders
    $files = $db->files_only();


### Deleting a file

    $response = $db->delete("uploaded.jpg");


## Configuration

Obtain an API key from Dropbox.
It is a private key and do not share to others.
Modify your config file.

`inc.config.php`

    require_once 'inc.config.php';
    
    $config = new config();
    $config->token = ""; // API Access Token
    $config->path = "/uploads";

    $db = new dropbox($config);
    
### How to obtain an API Key

[Create an App in Dropbox](https://www.dropbox.com/developers/apps).
Chose the following options.

 * *Create App*
 * Choose an API: *Dropbox API*
 * Choose the type of access: *App folder*
 * Name: Give some name (recommendation: SheepIT_[username])

Then, Generate OAuth 2 API Key with the options:

 * Allow implicit grant: *Allow*
 * *Generate access token*
 * Copy/Paste it to inc.config.php or use in cli
 * (Rename inc.config-sample.php to inc.config.php)

