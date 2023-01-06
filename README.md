# Fakturoplujka

## What am I?

Fakturoplujka is a small command line app, that allows you to generate beautiful 
invoices (in Polish format), using LaTeX

## Requirements:

You must have LaTeX and PHP installed on your system. Get LaTeX here: [The Latex Project](https://www.latex-project.org/get/)

## Usage:

Enter the appropriate data into the corresponding files in the ``config`` folder.
File structure is pretty self-explanatory. There you will find a sample configuration.

Fill seller.json with your data.

Create your own configuration, creating folders containing invoice.json and customer.json 
files with your customer and invoice data. 
The app will generate as many invoices as folders have been created.

To generate your invoices, just run following command:
```
php makeFV.php
```

## Have fun! 🥳