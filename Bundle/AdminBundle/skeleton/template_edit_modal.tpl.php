{# Instead of creating template for edition, you can render "@UmbrellaAdmin/edit_modal.html.twig" on controller #}
{% extends "@UmbrellaCore/Modal/form.html.twig" %}

{% block modal_title %}
    <h4 class="modal-title">{{ entity.id ? ('Edit' | trans) : ('Add' | trans) }}</h4>
{% endblock %}