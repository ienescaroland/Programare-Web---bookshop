<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * pbooks
 *
 * @ORM\Table(name="PBooks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\pbooksRepository")
 */
class PBooks
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_book", type="integer")
     */
    private $idBook;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer")
     */
    private $idUser;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idBook
     *
     * @param integer $idBook
     *
     * @return pbooks
     */
    public function setIdBook($idBook)
    {
        $this->idBook = $idBook;

        return $this;
    }

    /**
     * Get idBook
     *
     * @return int
     */
    public function getIdBook()
    {
        return $this->idBook;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return pbooks
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}

