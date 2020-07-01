<?php
class Event implements JsonSerializable
{
    private int $id;
    private string $name;
    private DateTime $date;
    private int $type;
    private string $place;
    private User $franchisee;

    private array $participants;
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
        $this->place = $parameters['place'];

        $uRepo = new UserRepository();
        $this->franchisee = $uRepo->getOneById($parameters['franchisee']);

        if (isset($parameters['participants'])){
            $participants = [];
            foreach($parameters['participants'] as $participant){
                $participants[] = $uRepo->getOneById($participant['id_user']);
            }
            $this->participants = $participants;
        }
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

    /**
     * @return mixed|string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed|string $place
     */
    public function setPlace($place): void
    {
        $this->place = $place;
    }

    /**
     * @return User
     */
    public function getFranchisee(): User
    {
        return $this->franchisee;
    }

    /**
     * @param User $franchisee
     */
    public function setFranchisee(User $franchisee): void
    {
        $this->franchisee = $franchisee;
    }

    /**
     * @return array
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param array $participants
     */
    public function setParticipants(array $participants): void
    {
        $this->participants = $participants;
    }



    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}