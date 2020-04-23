<?php


class User implements JsonSerializable
{

    private int $id;
    private string $firstname;
    private string $lastname;
    private string $password;
    private string $email;
    private string $phone;
    private string $street_name;
    private int $street_number;
    private string $city;
    private DateTime $date_register;
    private int $is_client;
    private int $is_worker;
    private int $is_employe;


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __construct(int $id,
                                string $firstname,
                                string $lastname,
                                string $password,
                                string $email,
                                string $phone,
                                string $street_name,
                                int $street_number,
                                string $city,
                                int $is_client,
                                int $is_worker,
                                int $is_employe)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->street_name = $street_name;
        $this->street_number = $street_number;
        $this->city = $city;
        $this->is_client = $is_client;
        $this->is_employe = $is_employe;
        $this->is_worker = $is_worker;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getStreetName(): string
    {
        return $this->street_name;
    }

    /**
     * @param string $street_name
     */
    public function setStreetName(string $street_name): void
    {
        $this->street_name = $street_name;
    }

    /**
     * @return int
     */
    public function getStreetNumber(): int
    {
        return $this->street_number;
    }

    /**
     * @param int $street_number
     */
    public function setStreetNumber(int $street_number): void
    {
        $this->street_number = $street_number;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return DateTime
     */
    public function getDateRegister(): DateTime
    {
        return $this->date_register;
    }

    /**
     * @param DateTime $date_register
     */
    public function setDateRegister(DateTime $date_register): void
    {
        $this->date_register = $date_register;
    }

    /**
     * @return int
     */
    public function isClient(): bool
    {
        if ($this->is_client != 0)
        return true;
        else return false;
    }

    /**
     * @param int $is_client
     */
    public function setIsClient(int $is_client): void
    {
        $this->is_client = $is_client;
    }

    /**
     * @return int
     */
    public function isWorker(): bool
    {
        if ($this->is_worker != 0)
            return true;
        else return false;
    }

    /**
     * @param int $is_worker
     */
    public function setIsWorker(int $is_worker): void
    {
        $this->is_worker = $is_worker;
    }

    /**
     * @return int
     */
    public function isEmploye(): bool
    {
        if ($this->is_employe != 0)
            return true;
        else return false;
    }

    /**
     * @param int $is_employe
     */
    public function setIsEmploye(int $is_employe): void
    {
        $this->is_employe = $is_employe;
    }




}