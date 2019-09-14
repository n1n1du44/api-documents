<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 22:18
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="document_file_format_storage")
 * @ORM\Entity
 * @ApiResource
 */
class DocumentFileFormatStorage
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  /**
   * @var Document
   *
   * @ManyToOne(targetEntity="Document", inversedBy="documentsFileFormatsStorages")
   * @JoinColumn(name="document_id", referencedColumnName="id")
   */
  private $document;

  /**
   * @var FileFormat
   *
   * @ManyToOne(targetEntity="FileFormat")
   * @JoinColumn(name="file_format_id", referencedColumnName="id")
   */
  private $fileFormat;

  /**
   * @var Storage
   *
   * @ManyToOne(targetEntity="Storage")
   * @JoinColumn(name="storage_id", referencedColumnName="id")
   */
  private $storage;

  /**
   * @var string
   *
   * @ORM\Column(type="string")
   */
  public $contentUrl;

  /**
   * @ORM\Column(name="relative_path", type="boolean")
   */
  private $relativePath;



  public function __construct(Document $document, FileFormat $fileFormat, Storage $storage, $contentUrl, $relativePath = true)
  {
    $this->document = $document;
    $this->fileFormat = $fileFormat;
    $this->storage = $storage;
    $this->contentUrl = $contentUrl;
    $this->relativePath = $relativePath;
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
   * @return Document
   */
  public function getDocument(): Document
  {
    return $this->document;
  }

  /**
   * @param Document $document
   */
  public function setDocument(Document $document): void
  {
    $this->document = $document;
  }

  /**
   * @return FileFormat
   */
  public function getFileFormat(): FileFormat
  {
    return $this->fileFormat;
  }

  /**
   * @param FileFormat $fileFormat
   */
  public function setFileFormat(FileFormat $fileFormat): void
  {
    $this->fileFormat = $fileFormat;
  }

  /**
   * @return string
   */
  public function getContentUrl(): string
  {
    return $this->contentUrl;
  }

  /**
   * @param string $contentUrl
   */
  public function setContentUrl(string $contentUrl): void
  {
    $this->contentUrl = $contentUrl;
  }

  /**
   * @return Storage
   */
  public function getStorage(): Storage
  {
    return $this->storage;
  }

  /**
   * @param Storage $storage
   */
  public function setStorage(Storage $storage): void
  {
    $this->storage = $storage;
  }

  /**
   * @return bool
   */
  public function isRelativePath(): bool
  {
    return $this->relativePath;
  }


}