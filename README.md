# Fakturoplujka

## What am I?

Fakturoplujka is a small command line app, that allows you to generate beautiful 
invoices (in Polish format) with single item, using LaTeX

## Requirements:

You must have LaTeX and PHP installed on your system. Get LaTeX here: [The Latex Project](https://www.latex-project.org/get/).
```bash
sudo apt install texlive
```
Also install Polish language if you want to use default template:

```bash
sudo apt install texlive-lang-polish
```


## Usage:

Enter the appropriate data into the corresponding files in the ``config`` folder. 
There you will find a sample configuration.
File structure is pretty self-explanatory. 

Fill seller.json with your data.

Create your own configuration, creating folders containing invoice.json and customer.json 
files with your customer and invoice data. 
The app will generate as many invoices as folders have been created.

To generate your invoices, just run following command:
```bash
php makeFV.php
```

## Have fun! 🥳
