<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 22:18
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="document")
 * @ORM\Entity
 * @ApiResource(
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     iri="http://schema.org/Document",
 *     normalizationContext={
 *         "groups"={"document_read"},
 *     },
 *     collectionOperations={
 *         "post"={
 *             "controller"=App\Controller\Webservice\CreateDocumentAction::class,
 *             "defaults"={
 *                 "_api_respond"=true,
 *             },
 *             "validation_groups"={"Default", "document_create"},
 *             "swagger_context"={
 *                 "consumes"={
 *                     "multipart/form-data",
 *                 },
 *                 "parameters"={
 *                     {
 *                         "in"="formData",
 *                         "name"="file",
 *                         "type"="file",
 *                         "description"="The file to upload",
 *                     },
 *                 },
 *             },
 *         },
 *         "get"={
 *             "controller"=App\Controller\Webservice\GetDocumentsAction::class,
 *          },
 *          "post_local"={
 *              "method"="POST",
 *              "path"="/document/create-local-document",
 *              "controller"=App\Controller\Webservice\CreateLocalDocumentAction::class,
 *             "defaults"={
 *                 "_api_receive"=false,
 *             },
 *              "swagger_context"={
 *                 "consumes"={
 *                     "application/json",
 *                 },
 *                "parameters"={
 *                  {
 *                    "name" = "data",
 *                    "in" = "body",
 *                    "required" = "true",
 *                    "schema" = {
 *                      "type" = "object",
 *                      "properties" = {
 *                        "localPath"={"type"="string"},
 *                        "format"={"type"="string"}
 *                      }
 *                    },
 *                  },
 *                }
 *              }
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *            "controller"=App\Controller\Webservice\GetDocumentAction::class,
 *          },
       *   "get_ocr"={
       *         "method"="GET",
       *         "path"="/documents/{id}/ocr",
       *         "controller"=App\Controller\Webservice\GetOcrDocumentAction::class,
       *     }
 *     },
 * )
 * @Vich\Uploadable
 */
class Document
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media_object_read", "document_read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @ApiProperty()
     * @Groups({"document_read"})
     * @Assert\NotNull(groups={"media_object_create"})
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    public $file;

    /**
     * @var string|null
     *
     * @ApiProperty()
     * @Groups({"document_read"})
     * @ORM\Column(nullable=true)
     */
    public $filePath;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

  /**
   * One Document has many DocumentFileFormat. This is the inverse side.
   * @OneToMany(targetEntity="DocumentFileFormatStorage", mappedBy="document")
   * @ApiProperty()
   */
  private $documentsFileFormatsStorages;

    public function __construct() {
      $this->documentsFileFormatsStorages = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
      return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
      $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
      return $this->contentUrl;
    }

    /**
     * @param string|null $contentUrl
     */
    public function setContentUrl(?string $contentUrl): void
    {
      $this->contentUrl = $contentUrl;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
      return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
      $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
      return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
      $this->filePath = $filePath;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
      return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
      $this->user = $user;
    }

  /**
   * @return mixed
   */
  public function getDocumentsFileFormatsStorages()
  {
    return $this->documentsFileFormatsStorages;
  }

  /**
   * @param mixed $documentsFileFormatsStorages
   */
  public function setDocumentsFileFormatsStorages($documentsFileFormatsStorages): void
  {
    $this->documentsFileFormatsStorages = $documentsFileFormatsStorages;
  }

  public function getDocumentFileFormatStorage(Storage $localStorage, FileFormat $fileFormat)
  {
    foreach ($this->documentsFileFormatsStorages as $documentFileFormatStorage) {
      if ($documentFileFormatStorage instanceof DocumentFileFormatStorage) {
        if (($documentFileFormatStorage->getStorage()->getCode() == $localStorage->getCode()) &&
          ($documentFileFormatStorage->getFileFormat()->getExtention() == $fileFormat->getExtention())) {
          return $documentFileFormatStorage;
        }
      }
    }
    return null;
  }


}