<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
        <title>Thats Weird</title>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-base.min.js"></script>
        <script type="text/javascript" src="{{ asset( 'assets/js/mainGraph.js' ) }}"></script>
        <link href="{{ asset('assets/css/mainGraph.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <link href="{{ asset('assets/css/material-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />
	</head>
	<body>
        <table>
                <tr class="nav">
                        <td><img src="{{ asset( 'assets/img/new.png' ) }}" id="new" /></td>
                        <td><img src="{{ asset( 'assets/img/logo.png' ) }}" id="logo" /></td>
                        <td><img src="{{ asset( 'assets/img/profile.png' ) }}" id="profile" /></td>
                </tr>
                <tr class="content" rowspan="2">
                        <td><i class="far fa-thumbs-down"></i></td>
                        <td>
                                <div id="container">
                                        <div class="card-body">
                                                <div class="row">
                                                        <div class="col-md-12">
                                                                <div class="form-group">
                                                                        <label class="bmd-label-floating">Title</label>
                                                                        <input
                                                                            name="title"
                                                                            class="form-control"
                                                                            value="{{ $formInfo->title ?? '' }}"
                                                                            Required
                                                                        />
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label class="bmd-label-floating">Category 1</label>
                                                                        <input
                                                                            name="category1"
                                                                            placeholder="Category 1"
                                                                            class="form-control"
                                                                            value="{{ $formInfo->category1 ?? '' }}"
                                                                            Required
                                                                        />
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                                <div class="form-group">
                                                                        <label class="bmd-label-floating">Category 2</label>
                                                                        <input
                                                                            name="category2"
                                                                            placeholder="Category 2"
                                                                            class="form-control"
                                                                            value="{{ $formInfo->category2 ?? '' }}"
                                                                        />
                                                                </div>
                                                        </div>
                                                        <textarea name="event" class="form-control" rows="5" Required>{{$formInfo->event ?? ''}}</textarea>
                                                <div class="col-md-12"></div>
                                                <button type="submit" class="btn btn-primary pull-right col-md-2" style="background-color:#1fba2d">
                                                        {{app('request')->input('id') == 'new' ? 'Create' : 'Edit' }}
                                                </button>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                </div>
                        </td>
                        <td><i class="far fa-thumbs-up"></i></td>
                </tr>
        </table>
        <script type="text/javascript" src="{{ asset( 'assets/js/mainGraph.js' ) }}">
		</script>
	</body>
</html>
