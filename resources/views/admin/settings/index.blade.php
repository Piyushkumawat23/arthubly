@extends('admin.layout.app')

@section('content')
    <div class="content-header">
        <h1 class="m-0">Global Settings</h1>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tabs-general-tab" data-bs-toggle="pill" href="#tabs-general"
                                role="tab" aria-controls="tabs-general" aria-selected="true"><i class="bi bi-gear"></i>
                                General Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabs-logo-tab" data-bs-toggle="pill" href="#tabs-logo" role="tab"
                                aria-controls="tabs-logo" aria-selected="false"><i class="bi bi-image"></i> Logo &
                                Favicon</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabs-seo-tab" data-bs-toggle="pill" href="#tabs-seo" role="tab"
                                aria-controls="tabs-seo" aria-selected="false"><i class="bi bi-globe"></i> Global SEO</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content" id="custom-tabs-four-tabContent">

                            <div class="tab-pane fade show active" id="tabs-general" role="tabpanel"
                                aria-labelledby="tabs-general-tab">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label>Website Name</label>
                                        <input type="text" name="website_name" class="form-control"
                                            value="{{ $setting->website_name ?? '' }}" placeholder="e.g. My E-Commerce">
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label>Contact Email</label>
                                        <input type="email" name="contact_email" class="form-control"
                                            value="{{ $setting->contact_email ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label>Contact Phone</label>
                                        <input type="text" name="contact_phone" class="form-control"
                                            value="{{ $setting->contact_phone ?? '' }}">
                                    </div>
                                    <div class="form-group col-md-12 mb-3">
                                        <label>Office Address</label>
                                        <textarea name="address" class="form-control" rows="3">{{ $setting->address ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabs-logo" role="tabpanel" aria-labelledby="tabs-logo-tab">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label>Website Logo</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        @if (isset($setting->logo))
                                            <div class="mt-2 p-2 bg-light border rounded" style="width: fit-content;">
                                                <img src="{{ asset('public/uploads/settings/' . $setting->logo) }}"
                                                    alt="Logo" style="max-height: 80px;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label>Favicon (Browser Tab Icon)</label>
                                        <input type="file" name="favicon" class="form-control" accept="image/*">
                                        @if (isset($setting->favicon))
                                            <div class="mt-2 p-2 bg-light border rounded" style="width: fit-content;">
                                                <img src="{{ asset('uploads/settings/' . $setting->favicon) }}" alt="Favicon"
                                                    style="max-height: 32px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tabs-seo" role="tabpanel" aria-labelledby="tabs-seo-tab">
                                <div class="row">
                                    <div class="form-group col-md-12 mb-3">
                                        <label>Global SEO Title</label>
                                        <input type="text" name="seo_title" class="form-control"
                                            value="{{ $setting->seo_title ?? '' }}"
                                            placeholder="Max 60 characters recommended">
                                    </div>
                                    <div class="form-group col-md-12 mb-3">
                                        <label>Global SEO Description</label>
                                        <textarea name="seo_description" class="form-control" rows="3" placeholder="Max 160 characters recommended">{{ $setting->seo_description ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12 mb-3">
                                        <label>SEO Keywords (Comma separated)</label>
                                        <textarea name="seo_keywords" class="form-control" rows="2"
                                            placeholder="e.g. shoes, online shopping, cheap clothes">{{ $setting->seo_keywords ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save
                                Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
