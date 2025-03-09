{# Instead of creating template for edition, you can render "@UmbrellaAdmin/edit.html.twig" on controller #}
{% extends "@UmbrellaAdmin/layout.html.twig" %}

{% block breadcrumb %}
    {% set admin_breadcrumb = get_breadcrumb(admin_menu, {}, (entity.id ? 'Edit' : 'Add') | trans) %}
    {{ render_breadcrumb(admin_breadcrumb) }}
{% endblock %}


{% block content %}
    <div class="card">
        <div class="card-body">
            {{ umbrella_form_theme(form) }}
            {{ form_start(form) }}
                {{ form_errors(form) }}
                {{ form_rest(form) }}

                <div>
                    <a href="{{ path('<?= $route['name_prefix'] ?>_index') }}" class="btn btn-link">
                        <i class="mdi mdi-chevron-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check"></i> {{ 'Save' | trans }}
                    </button>
                </div>
            {{ form_end(form) }}
        </div>
    </div>
{% endblock %}
