Moodle 2.3 plugin: EasyOChem Marvinsketch Mechanism (EasyOMech) question type

Carl LeBlond


INSTALLATION:

This will NOT work with Moodle 2.0 or older, since it uses the new
question API implemented in Moodle 2.1.

This is a Moodle question type. It should come as a self-contained 
"easyomech" folder which should be placed inside the "question/type" folder
which already exists on your Moodle web server.

Once you have done that, visit your Moodle admin page - the database 
tables should automatically be upgraded to include an extra table for
the EasyOChem Mechanism question type.

You must download a recent copy of Marvin Applets from www.chemaxon.com (free for academic use)
and intall it in folder named "marvin" at your web root.  Alternatively 
you could edit the php scripts if your marvin installation is elsewhere.

I have no affiliation with Chemaxon.

USAGE:

The EasyoChem Curved Arrow / Electron Pushing question can be used to test and strengthen 
students knowledge of reaction mechanism, resonance and curved arrow notation.

You can ask questions such as;

    Please add curved arrows showing the flow of electrons for the following reaction?
    Please add curved arrows showing how the following resonance structure could be obtained?
