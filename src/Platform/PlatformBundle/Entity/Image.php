<?php

namespace Platform\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="pl_image")
 * @ORM\Entity(repositoryClass="Platform\PlatformBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="url", type="string", length=255)
   */
  private $url;

  /**
   * @ORM\Column(name="alt", type="string", length=255)
   */
  private $alt;

  private $file;

  private $tmpFileName;

    public function getFile(){
        return $this->file;
    }

    public function setFile(UploadedFile $file){
        $this->file = $file;

        if(null !== $this->url){
            $this->tmpFileName = $this->url;

            $this->url = null;
            $this->alt = null;
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        if(null === $this->file){
            return;
        }

        $this->url = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(){
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if(null === $this->file){
            return;
        }

        if(null !== $this->tmpFileName){
            $oldfile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tmpFileName;
            if(file_exists($oldfile)){
                unlink($oldfile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->url
        );
            
        // // On récupère le nom original du fichier de l'internaute
        // $name = $this->file->getClientOriginalName();
        
        // // On déplace le fichier envoyé dans le répertoire de notre choix
        // $this->file->move($this->getUploadRootDir(), $name);
        
        // // On sauvegarde le nom de fichier dans notre attribut $url
        // $this->url = $name;
        
        // // On crée également le futur attribut alt de notre balise <img>
        // $this->alt = $name;

    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        $this->tmpFileName = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(){
        if(file_exists($this->tmpFileName)){
            unlink($this->tmpFileName);
        }
        else{
            $test = array('saad','yousfi');
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath(){
        return $this->getUploadDir().'/'.$this->id.'.'.$this->url;
    }
}
