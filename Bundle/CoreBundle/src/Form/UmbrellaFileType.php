<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\HtmlUtils;

// Limitation : HTML5 required dos not work.

/**
 * Class UmbrellaFileType
 */
class UmbrellaFileType extends AbstractType
{
    private EntityManagerInterface $em;
    private UmbrellaFileHelper $fileHelper;

    /**
     * UmbrellaFileType constructor.
     */
    public function __construct(?EntityManagerInterface $em = null, ?UmbrellaFileHelper $fileHelper = null)
    {
        // Trying to instanciate FormType because UmbrellaFileType is not registered on container (issue with poor symfony form factory logic)
        if (null === $fileHelper || null === $em) {
            throw new \LogicException('Enable umbrella_core.file to use UmbrellaFileType');
        }

        $this->em = $em;
        $this->fileHelper = $fileHelper;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var ?UmbrellaFile $umbrellaFile */
        $umbrellaFile = $form->getData();

        if (null === $umbrellaFile || null === $umbrellaFile->id) {
            $view->vars['file_info'] = '';
        } else {
            $view->vars['file_info'] = sprintf(
                '<a href="%s" class="text-primary" download><i class="mdi mdi-download mr-1"></i>%s %s</a>',
                $this->fileHelper->getUrl($umbrellaFile),
                HtmlUtils::escape($umbrellaFile->name),
                $umbrellaFile->getHumanSize()
            );
        }

        $view->vars['allow_delete'] = $options['allow_delete'];
        $view->vars['label_browse'] = $options['label_browse'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_uploadedFile', FileType::class, [
            'required' => false,
            'error_bubbling' => true, // pass error to the parent
            'attr' => $options['attr'],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) use ($options) {
            $this->preSetData($event, $options);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) use ($options) {
            $this->submit($event, $options);
        });
    }

    protected function preSetData(PreSetDataEvent $event, array $options)
    {
        $data = $event->getData();

        if ($data && $options['allow_delete']) {
            $event->getForm()->add('_deleteFile', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
            ]);
        }
    }

    protected function submit(SubmitEvent $event, array $options)
    {
        $form = $event->getForm();

        /** @var ?UmbrellaFile $file */
        $file = $event->getData();

        if (null === $file) {
            return; // no upload was performed
        }

        $uploadedFile = $file->_uploadedFile;

        if ($options['config_name']) {
            $file->configName = $options['config_name'];
        }

        // delete current uploaded file !
        if (null === $uploadedFile && $form->has('_deleteFile') && $form->get('_deleteFile')->getData()) {
            $this->em->remove($file);
            $event->setData(null);

            return;
        }

        // unpersisted umbrellafile + no file uploaded => return  null
        if (null === $uploadedFile && null === $file->id) {
            $file = null;
            $event->setData(null);

            return;
        }

        // persisted umbrellafile + file uploaded => remove previous // new current
        if (null !== $uploadedFile && null !== $file->id) {
            $this->em->remove($file);

            $newFile = clone $file;
            $newFile->_uploadedFile = $uploadedFile;
            $event->setData($newFile);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'config_name' => null,
            'data_class' => UmbrellaFile::class,
            'error_bubbling' => false, // resolve error at this level
            'allow_delete' => true,
            'label_browse' => 'common.browse',
            'mapping' => null
        ]);

        $resolver->setAllowedTypes('config_name', ['null', 'string']);
        $resolver->setAllowedTypes('allow_delete', 'boolean');
        $resolver->setAllowedTypes('mapping', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'umbrellafile';
    }
}
