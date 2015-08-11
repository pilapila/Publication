<?php

namespace LyndaNew\MagazineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Publication
 *
 * @ORM\Table(name="publications")
 * @ORM\Entity(repositoryClass="LyndaNew\MagazineBundle\Entity\PublicationRepository")
 */
class Publication
{
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="publication")
     */
    private $issues;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;
    
    public function __construct()
    {
        $this->issues = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Publication
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Publication
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add issues
     *
     * @param \LyndaNew\MagazineBundle\Entity\Issue $issues
     * @return Publication
     */
    public function addIssue(\LyndaNew\MagazineBundle\Entity\Issue $issues)
    {
        $this->issues[] = $issues;

        return $this;
    }

    /**
     * Remove issues
     *
     * @param \LyndaNew\MagazineBundle\Entity\Issue $issues
     */
    public function removeIssue(\LyndaNew\MagazineBundle\Entity\Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues()
    {
        return $this->issues;
    }
    
    /**
     * Render a Publication as a string.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Get web path to upload directory.
     * 
     * @return string
     *   Relative path.
     */
    protected function getUploadPath()
    {
        return 'uploads/profilePictures';
    }
    
    
    /**
     * Get absolute path to upload directory.
     * 
     * @return string
     *   Absolute path.
     */
    protected function getUploadAbsolutePath()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadPath();
    }
    
    /**
     * Get web path to a picture.
     * 
     * @return null|string
     *   Relative path.
     */
    public function getPictureWeb() {
        return NULL === $this->getPicture()
                ? NULL
                : $this->getUploadPath() . '/' . $this->getPicture();
    }
    
    /**
     * Get path on disk to a picture.
     * 
     * @return null|string
     *   Absolute path.
     */
    public function getPictureAbsolute() {
        return NULL === $this->getPicture()
                ? NULL
                : $this->getUploadAbsolutePath() . '/' . $this->getPicture();
    }

    
    
    /**
     * @Assert\File(maxSize="1000000")
     */
    private $file;
    
    /**
     * Sets file.
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file = NULL) {
        $this->file = $file;
    }
    
    /**
     * Get file.
     * 
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Upload a profilePicture file.
     */
    public function upload() {
        // File property can be empty.
        if (NULL === $this->getFile()) {
            return;
        }
        
        $filename = $this->getFile()->getClientOriginalName();
        
        // Move the uploaded file to target directory using original name.
        $this->getFile()->move(
                $this->getUploadAbsolutePath(),
                $filename);
        
        // Set the profilePicture.
        $this->setPicture($filename);
        
        // Cleanup.
        $this->setFile();
    }        
    
}

