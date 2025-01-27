# Fakturoplujka

## What am I?

Fakturoplujka is a small command line app, that allows you to generate beautiful 
invoices (in Polish format) with single item, using LaTeX


## Usage:

Enter the appropriate data into the corresponding files in the ``config`` folder. 
There you will find a sample configuration.
File structure is pretty self-explanatory. 

Fill seller.json with your data.

Create your own configuration, creating folders containing invoice.json and customer.json 
files with your customer and invoice data. 
The app will generate as many invoices as folders have been created.

To test everything, run the app with the following command:

```bash
make first
```

If everything is okay, you can generate invoices with the following command:

```bash
make fv
```

## Have fun! 🥳
