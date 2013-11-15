<html>
  <head>

    <link href="themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
	<link href="scripts/jtable/themes/lightcolor/blue/jtable.css" rel="stylesheet" type="text/css" />

	<script src="scripts/jquery-1.6.4.min.js" type="text/javascript"></script>
    <script src="scripts/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
    <script src="scripts/jtable/jquery.jtable.js" type="text/javascript"></script>
    <script src="scripts/jtable/extensions/jquery.jtable.editinline.js"></script>
    <script src="scripts/jtable/localization/jquery.jtable.de.js" type="text/javascript"></script>

  </head>
  <body>
  <div id="Wrapper" style="width:100%">
    <!-- Headline Section -->
    <div id="Headline" style="height:100px; background: url(themes/redmond/images/ui-bg_gloss-wave_55_5c9ccc_500x100.png); background-repeat:repeat-x; margin: 1%">
       <b style="font-size:150%"><center>smartDB</center></b>
    </div>
    <!-- Menu Section -->
    <div id="Menu" style="height:600px; background-color: #EEEEEE; background-repeat:repeat-x; width:13%; float:left; margin-left: 1%; margin-right: 1% ">
      <b>Hauptmenü</b><br />
      Mitglieder<br />
      <a href="Geburtstage.php" target="_blank">Geburtstagsliste</a><br />
      Impfliste
    </div>
    <!-- Search bar -->
    <div class="filtering">
    <form>
        Name: <input type="text" name="name" id="name" />
        <button type="submit" id="LoadRecordsButton">Suchen</button>
    </form>
    </div>
    <!-- JTable Section - Main Content -->
    <div id="UserTableContainer" style="width: 84%; float:left;"></div>
	<script type="text/javascript">

		$(document).ready(function () {

		    //Prepare jTable
			$('#UserTableContainer').jtable({
				title: 'HSVRM-Mitglieder',
				paging: true,
				pageSize: 10,
				sorting: true,
				defaultSorting: 'Name ASC',
                //editinline:{enable:true},
				actions: {
					listAction: 'userActions.php?action=listByFilt',
					createAction: 'userActions.php?action=create',
					updateAction: 'userActions.php?action=update',
					deleteAction: 'userActions.php?action=delete'
				},
				fields: {
					PersonID: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
                    Telefon: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    display: function (UserData) {
                        //Create an image that will be used to open child table
                        var $img = $('<img src="/themes/base/images/phone.png" title="Telefonnummern bearbeiten" />');
                        //Open child table when user clicks the image
                        $img.click(function () {
                            $('#UserTableContainer').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: UserData.record.Vorname + ' ' + UserData.record.Name + ' - Telefon',
                                        actions: {
                                            listAction: 'phoneActions.php?PersonID=' + UserData.record.PersonID + '&action=list',
                                            createAction: 'phoneActions.php?PersonID=' + UserData.record.PersonID + '&action=create',
                                            updateAction: 'phoneActions.php?PersonID=' + UserData.record.PersonID + '&action=update',
                                            deleteAction: 'phoneActions.php?PersonID=' + UserData.record.PersonID + '&action=delete'
                                        },
                                        fields: {
                                            TelefonID: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            PersonID: {
                                                type: 'hidden',
                                                defaultValue: UserData.record.PersonID
                                            },
                                            Typ: {
                                                title: 'Telefon',
                                                width: '30%',
                                                options: { 'privat': 'privat', 'mobil': 'mobil', 'dienstl.': 'dienstl.' }
                                            },
                                            Nummer: {
                                                title: 'Rufnummer',
                                                width: '30%'
                                            }
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                        });
                           //Return image to show on the person row
                           return $img;
                        }
                    },
                    Email: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    display: function (UserData) {
                        //Create an image that will be used to open child table
                        var $img = $('<img src="/themes/base/images/email.png" title="Emailadressen bearbeiten" />');
                        //Open child table when user clicks the image
                        $img.click(function () {
                            $('#UserTableContainer').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: UserData.record.Vorname + ' ' + UserData.record.Name + ' - Email',
                                        actions: {
                                            listAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=list',
                                            createAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=create',
                                            updateAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=update',
                                            deleteAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=delete'
                                        },
                                        fields: {
                                            EmailID: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            PersonID: {
                                                type: 'hidden',
                                                defaultValue: UserData.record.PersonID
                                            },
                                            Typ: {
                                                title: 'Email',
                                                width: '30%',
                                                options: { 'privat': 'privat',  'dienstl.': 'dienstl.' ,  'Verein': 'Verein'}
                                            },
                                            Email: {
                                                title: 'Adresse',
                                                width: '30%'
                                            }
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                        });
                           //Return image to show on the person row
                           return $img;
                        }
                    },
                    Hund: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    display: function (UserData) {
                        //Create an image that will be used to open child table
                        var $img = $('<img src="/themes/base/images/dog.png" title="Hundedaten bearbeiten" />');
                        //Open child table when user clicks the image
                        $img.click(function () {
                            $('#UserTableContainer').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: UserData.record.Vorname + ' ' + UserData.record.Name + ' - Hundedaten',
                                        actions: {
                                            listAction: 'dogActions.php?PersonID=' + UserData.record.PersonID + '&action=list'
                                            //createAction: 'dogActions.php?PersonID=' + UserData.record.PersonID + '&action=create',
                                            //updateAction: 'dogActions.php?PersonID=' + UserData.record.PersonID + '&action=update',
                                            //deleteAction: 'dogActions.php?PersonID=' + UserData.record.PersonID + '&action=delete'
                                        },
                                        fields: {
                                            HundID: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            PersonID: {
                                                type: 'hidden',
                                                defaultValue: UserData.record.PersonID
                                            },

                                            Chip: {
                                                   title: '',
                                                   width: '5%',
                                                   sorting: false,
                                                   edit: false,
                                                   create: false,
                                                   display: function (DogData) {
                                                            //Create an image that will be used to open child table
                                                            var $imgdetalhe = $('<img src="/themes/base/images/chip.png" title="Chip/Täto" />');
                                                            //Open child table when user clicks the image
                                                            $imgdetalhe.click(function () {
                                                            $imgdetalhe.closest('.jtable-child-table-container').jtable('openChildTable', $imgdetalhe.closest('tr'),
                                                              {
                                                               title: DogData.record.Rufname + ' - ' + DogData.record.Name + ' - Chip /Täto',
                                                               actions: {
                                                                        listAction: 'chipActions.php?HundID=' + DogData.record.HundID + '&action=list'
                                                                        //createAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=create',
                                                                        //updateAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=update',
                                                                        //deleteAction: 'emailActions.php?PersonID=' + UserData.record.PersonID + '&action=delete'
                                                                        },
                                                               fields: {
                                                                       HundID: {
                                                                               key: true,
                                                                               create: false,
                                                                               edit: false,
                                                                               list: false
                                                                               },
                                                                       Typ: {
                                                                               title: 'Typ',
                                                                               width: '30%',
                                                                               options: { 'Chip': 'Chip',  'Tätowierung': 'Täto' }
                                                                               },
                                                                       Kennzeichnung: {
                                                                                      title: 'Kennzeichnung',
                                                                                      width: '30%'
                                                                                      }
                                                                       }
                                                               },
                                                               function (data) { //opened handler
                                                               data.childTable.jtable('load');
                                                               });
                                                            });
                                                            //Return image to show on the person row
                                                            return $imgdetalhe;
                                                    }
                                            },
                                            Rufname: {
                                                title: 'Rufname',
                                                width: '10%',
                                            },
                                            Zwingername: {
                                                title: 'Zwingername',
                                                width: '10%'
                                            },
                                            Name: {
                                                title: 'Rasse',
                                                width: '10%',
                                            },
                                            Geschlecht: {
                                                title: 'R/H',
                                                width: '5%',
                                                options: { 'R': 'Rüde', 'H': 'Hündin' }
                                            },
                                            Wurftag: {
                                                title: 'Wurftag',
                                                width: '10%',
                                                type: 'date',
                                                //displayFormat: 'dd.mm.yy'
                                            },
                                            Farbe: {
                                                title: 'Farbe',
                                                width: '10%'
                                            },
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                        });
                           //Return image to show on the person row
                           return $img;
                        }
                    },
					Name: {
						title: 'Name',
						width: '10%'
					},
                    Vorname: {
						title: 'Vorname',
						width: '20%'
					},
                   Geburtstag: {
						title: 'Geburtstag',
						width: '15%',
                        type: 'date',
                        //displayFormat: 'dd.mm.yy'
					},
					Geschlecht: {
						title: 'm/w',
                        options: { 'm': 'männlich', 'w': 'weiblich' },
						width: '15%'
					},
                    HSVRMNr: {
						title: 'HSVRM-Nr.',
						width: '15%'
					},
                   Status: {
						title: 'Status',
                        options: {'Vollmitglied': 'Vollmitglied (42,00€/Jahr)','Jugendbeitrag': 'Jugendbeitrag (10,00€/Jahr)','Familienbeitrag': 'Familienbeitrag (80,00€/Jahr)','Ehrenmitglied': 'Ehrenmitglied (beitragsbefreit)'},
						width: '15%'
					},
                   AdressID: {
                        //title: 'AdressID',
						//width: '10%'
                        edit: false,
                        create: false,
 						list: false
					}
				}
			});

            //Re-load records when user click 'load records' button.
            $('#LoadRecordsButton').click(function (e) {
              e.preventDefault();
              $('#UserTableContainer').jtable('load', {
                name: $('#name').val(),
              });
            });

        //Load all records when page is first shown
        $('#LoadRecordsButton').click();

  });

	</script>
  </div>
  </body>
</html>
