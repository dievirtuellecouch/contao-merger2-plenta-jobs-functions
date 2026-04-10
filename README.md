# Additional merger² Functions for Plenta Jobs

This bundle adds additional functions to *merger²* which allow querying *Plenta Jobs*. See the [merger² documentation](https://github.com/contao-community-alliance/merger2/wiki) for more information on how to use those functions.

It is intended for Contao 5 projects with `dvc/merger2` `1.0.0` and `plenta/contao-jobs-basic-bundle` `^3.1`.

## Employment Type

**jobEmploymentType(expectedType: `string`)**

Check if the Job Offer on the current detail page (based on the `auto_item` of the URL path) has the given employment type. The expected type attribute is the type to test for, which should be provided as the key *Plenta Jobs* uses internally. *Plenta Jobs* provides some predefined employment types and allows to create additional custom types.

See `Plenta\ContaoJobsBasic\Helper\EmploymentType::getGoogleForJobsEmploymentTypes` for a full list of available keys. For example, to test for a full-time job, use `jobEmploymentType(FULL_TIME)`.

Custom employment types are provided by *Plenta Jobs* as a string with the schema `CUSTOM_{id}` where `id` is the id of the employment type as stored in the database. For employment type with id 1, use the query `jobEmploymentType(CUSTOM_1)`.

For legacy data the function accepts both the current JSON format from Plenta Jobs and older serialized Contao array values in the `employmentType` field.
