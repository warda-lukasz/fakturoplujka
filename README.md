# Fakturoplujka

## What am I?

Fakturoplujka is a small command line app, that allows you to generate beautiful 
invoices (in Polish format) with single item, using LaTeX

## Usage:

Prepare your configuration file in the ``config`` folder. You can use the sample
configuration file as a template.

```
cp config/config.yml.dist config/config.yml
```


To test everything, run the app with the following command:

```bash
make first
```

If everything is okay, you can generate invoices with the following command:

```bash
make fv
```

## Have fun! 🥳
