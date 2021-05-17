<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

    <title> {{ App\AppCustom\Models\Company::find(session('id_company'))->name_org }} </title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Use the correct meta names below for your web application
             Ref: http://davidbcalhoun.com/2010/viewport-metatag 

    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href={{asset("css/bootstrap.min.css")}}>
    <link rel="stylesheet" type="text/css" media="screen" href={{asset("css/font-awesome.min.css")}}>
    <link rel="stylesheet" type="text/css" media="screen" href={{asset("css/ionicons.min.css")}}>

    <!-- Select2 -->
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="css/select2.css"> -->

    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
    <link rel="stylesheet" type="text/css" media="screen" href={{asset("css/smartadmin-production.css")}}>
    <link rel="stylesheet" type="text/css" media="screen" href={{asset("css/smartadmin-skins.css")}}>


    <!-- Data Table -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">

    <!-- SmartAdmin RTL Support is under construction
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->
    
	<link rel="stylesheet" type="text/css" media="screen" href={{asset("css/appCustom.css?_nc=".time() )}}>

    <!-- FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico?v=1" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico?v=1" type="image/x-icon">

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <link media="screen" type="text/css" rel="stylesheet" href={{asset("css/editor.dataTables.min.css")}}>
	
	<link media="screen" type="text/css" rel="stylesheet" href={{asset("css/datepicker3.css")}}>
    <link media="screen" type="text/css" rel="stylesheet" href={{asset("css/fontawesome-iconpicker.min.css")}}>

    <!-- Include external CSS FROALA. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
    
    <!-- Include Editor style FROALA. -->
    <link href={{asset("js/plugin/froala_editor_2.6.4/css/froala_editor.pkgd.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("js/plugin/froala_editor_2.6.4/css/froala_style.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("js/plugin/froala_editor_2.6.4/css/plugins/special_characters.min.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("js/plugin/froala_editor_2.8.1/css/plugins/table.min.css")}} rel="stylesheet" type="text/css" />
    <link href={{asset("js/plugin/froala_editor_2.8.1/css/plugins/colors.min.css")}} rel="stylesheet" type="text/css" />
    <!-- Include the fonts to froala. -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oswald:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,greek,greek-ext,vietnamese,cyrillic-ext,cyrillic,latin-ext' rel='stylesheet' type='text/css'>
                
</head>