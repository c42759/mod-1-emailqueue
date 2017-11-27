# Mod Email Queue
[for BO3-BOzon](https://github.com/One-Shift/BO3-BOzon)


### Windows

#### Schedule Task

[Youtube Video](https://youtu.be/s_EMsHlDPnE)

Program/Script (in my case, I'm using XAMPP): C:\xampp\php\php.exe
Arguments: C:/xampp/htdocs/backoffice/cron/mod-emailqueue-cron.php


#### Run Every Minute

1) Double click the task and a property window will show up.
2) Click the Triggers tab.
3) Double click the trigger details and the Edit Trigger window will show up.
4) Under Advance settings panel, tick Repeat task every xxx minutes, and set Indefinitely if you need.
5) Finally, click ok.

[More info here](https://stackoverflow.com/a/4250516/3083653)
C:\Xampp\php\php.exe -f C:/xampp/htdocs/backoffice/cron/mod-emailqueue-cron.php

[put task in silent mode](https://stackoverflow.com/a/6568823/3083653)

### Linux

### Mac OS