{% extends "@UmbrellaAdmin/edit_modal.html.twig" %}
{% block modal_title %}
    <h4 class="modal-title">{{ entity.id ? ( 'action.edit_<?= $i18n_id; ?>' | trans) : ( 'action.add_<?= $i18n_id; ?>' | trans) }}</h4>
{% endblock %}