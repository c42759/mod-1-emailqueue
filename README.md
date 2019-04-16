# Mod Email Queue
[for BO3-BOzon](https://github.com/One-Shift/BO3-BOzon)


### Windows

#### Schedule Task

[Youtube Video](https://youtu.be/s_EMsHlDPnE)

Program/Script (in my case, I'm using XAMPP): ```C:\xampp\php\php.exe```

Arguments: ```C:/xampp/htdocs/backoffice/cron/mod-emailqueue-cron.php```


#### Run Every Minute

1) Double click the task and a property window will show up.
2) Click the Triggers tab.
3) Double click the trigger details and the Edit Trigger window will show up.
4) Under Advance settings panel, tick Repeat task every xxx minutes, and set Indefinitely if you need.
5) Finally, click ok.

[More info here](https://stackoverflow.com/a/4250516/3083653)

[Put task in silent mode](https://stackoverflow.com/a/6568823/3083653)

### Linux

[howtogeek website, learn how to use crontab](https://www.howtogeek.com/101288/how-to-schedule-tasks-on-linux-an-introduction-to-crontab-files/)

```* * * * * php -f /opt/lampp/htdocs/backoffice/cron/mod-emailqueue-cron.php```

### Mac OS



## How I use it

**1)** Start a new object

```PHP
$email = new c1_emailqueue();
```

**2)** Get all your email settings,

```PHP
$settings = c1_emailqueue::getSettings();
$email->set_field_settings(json_encode($settings));
```

**2.1)**  You can recreate this ARRAY with other settings for specific cases and give him to email object,

```PHP
$email->set_field_settings(json_encode($your_settings_array));
```

**3)** Set all attributes you need,

```PHP
$email->setFrom($settings['server_email']);
$email->setTo('target@email.ext');
$email->setCc('target_2@email.ext; target_3@email.ext'); // not mandatory
$email->setBcc('target_4@email.ext; target_5@email.ext'); // not mandatory
$email->setSubject('Give me a subject');
$email->setContent('Some content here!'); // give him HTML ;) it works very well
$email->setAttachments([
    'uploads/file_1.jpg',
    'uploads/file_2.txt'
]); // doesn't forget the size limit of your email service
$email->setPriority(10); // use if you want some priority over other emails on the pool (Default: 0)
$email->setStatus(); // not mandatory (Default: false or 0)
```

**4)** Send to the email pool and the *cron* will do the rest.

```PHP
if ($email->insert()) {
	// do something, or alert the user with a success message
} else {
    // do something, or alert the user with a error message
    // in this case don't forget, give developer an alert
}
```



## Things for future

- [ ] Update method;
- [ ] Exceptions;
- [ ] Data control;