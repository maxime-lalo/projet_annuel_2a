<?php
class Event implements JsonSerializable
{
    private int $id;
    private string $name;
    private DateTime $date;
    private int $type;

    /**
     * Event constructor.
     * @param array $parameters
     * @throws Exception
     */
    public function __construct(array $parameters)
    {
        $this->id = $parameters['id'];
        $this->name = $parameters['name'];
        $this->date = new DateTime($parameters['date']);
        $this->type = $parameters['type'];
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed|string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int|mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int|mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}